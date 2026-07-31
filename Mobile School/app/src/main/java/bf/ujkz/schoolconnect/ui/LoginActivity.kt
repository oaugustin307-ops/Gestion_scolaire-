package bf.ujkz.schoolconnect.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import bf.ujkz.schoolconnect.databinding.ActivityLoginBinding
import bf.ujkz.schoolconnect.models.LoginRequest
import bf.ujkz.schoolconnect.network.RetrofitClient
import bf.ujkz.schoolconnect.utils.SessionManager
import kotlinx.coroutines.launch

/**
 * Écran de connexion du parent.
 *
 * Fonctionnement :
 * 1. Le parent saisit son email et son mot de passe.
 * 2. On appelle POST /api/guardian/login via Retrofit.
 * 3. Laravel répond avec un cookie de session (géré automatiquement par
 *    PersistentCookieJar, voir network/RetrofitClient.kt) + les infos du parent.
 * 4. On enregistre ces infos localement (SessionManager) pour ne pas avoir
 *    à se reconnecter à chaque ouverture de l'app.
 * 5. On redirige vers MainActivity (liste des enfants).
 */
class LoginActivity : AppCompatActivity() {

    private lateinit var binding: ActivityLoginBinding
    private lateinit var sessionManager: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        sessionManager = SessionManager(this)

        // Si une session locale existe déjà (le parent ne s'est pas déconnecté
        // depuis la dernière utilisation), on saute directement à l'écran principal.
        // Le cookie de session côté serveur peut toutefois avoir expiré : dans ce
        // cas, MainActivity détectera un code 401 et renverra ici automatiquement.
        if (sessionManager.isLoggedIn()) {
            goToMain()
            return
        }

        binding.btnLogin.setOnClickListener { attemptLogin() }
    }

    /** Valide le formulaire, puis envoie la requête de connexion à l'API. */
    private fun attemptLogin() {
        val email = binding.etEmail.text?.toString()?.trim().orEmpty()
        val password = binding.etPassword.text?.toString()?.trim().orEmpty()

        binding.tvError.visibility = View.GONE

        // Validation simple côté client avant d'appeler le réseau (évite un
        // aller-retour serveur inutile pour une erreur de saisie évidente).
        if (email.isEmpty() || password.isEmpty()) {
            showError("Merci de remplir l'email et le mot de passe.")
            return
        }

        setLoading(true)

        // lifecycleScope.launch : lance une coroutine liée au cycle de vie de
        // l'Activity. Si l'utilisateur quitte l'écran avant la réponse réseau,
        // la coroutine est annulée automatiquement (pas de fuite mémoire/crash).
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@LoginActivity)
                val response = api.login(LoginRequest(email, password))

                if (response.isSuccessful && response.body()?.success == true) {
                    // Connexion réussie : on récupère les infos du parent renvoyées
                    // par GuardianApiController::login() et on les sauvegarde.
                    val guardian = response.body()?.data?.guardian
                    if (guardian != null) {
                        sessionManager.saveGuardian(guardian)
                        goToMain()
                    } else {
                        showError("Réponse inattendue du serveur.")
                    }
                } else if (response.code() == 401) {
                    // 401 = identifiants invalides (cf. GuardianApiController::login)
                    showError("Email ou mot de passe incorrect.")
                } else {
                    showError(response.body()?.message ?: "Erreur de connexion (code ${response.code()}).")
                }
            } catch (e: Exception) {
                // Cas typique : serveur Laravel non démarré, mauvaise IP/port,
                // ou téléphone/PC pas sur le même réseau.
                showError("Impossible de contacter le serveur. Vérifiez votre connexion et que l'API tourne bien.")
            } finally {
                setLoading(false)
            }
        }
    }

    private fun showError(message: String) {
        binding.tvError.text = message
        binding.tvError.visibility = View.VISIBLE
    }

    private fun setLoading(loading: Boolean) {
        binding.progressBar.visibility = if (loading) View.VISIBLE else View.GONE
        binding.btnLogin.isEnabled = !loading
    }

    private fun goToMain() {
        startActivity(Intent(this, MainActivity::class.java))
        finish() // empêche de revenir sur le login avec le bouton "retour"
    }
}
