package bf.ujkz.schoolconnect.ui

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import bf.ujkz.schoolconnect.databinding.ActivityChangePasswordBinding
import bf.ujkz.schoolconnect.models.ChangePasswordRequest
import bf.ujkz.schoolconnect.network.RetrofitClient
import kotlinx.coroutines.launch

class ChangePasswordActivity : AppCompatActivity() {

    private lateinit var binding: ActivityChangePasswordBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityChangePasswordBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.toolbar.setNavigationIcon(android.R.drawable.ic_menu_close_clear_cancel)
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        binding.btnSubmit.setOnClickListener { submit() }
    }

    private fun submit() {
        val current = binding.etCurrentPassword.text?.toString().orEmpty()
        val newPass = binding.etNewPassword.text?.toString().orEmpty()
        val confirm = binding.etConfirmPassword.text?.toString().orEmpty()

        hideMessage()

        if (current.isEmpty() || newPass.isEmpty() || confirm.isEmpty()) {
            showMessage("Merci de remplir tous les champs.", isError = true)
            return
        }
        if (newPass.length < 6) {
            showMessage("Le nouveau mot de passe doit contenir au moins 6 caractères.", isError = true)
            return
        }
        if (newPass != confirm) {
            showMessage("La confirmation ne correspond pas au nouveau mot de passe.", isError = true)
            return
        }

        setLoading(true)
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@ChangePasswordActivity)
                val response = api.changePassword(
                    ChangePasswordRequest(current, newPass, confirm)
                )

                if (response.isSuccessful && response.body()?.success == true) {
                    showMessage("Mot de passe modifié avec succès.", isError = false)
                    binding.etCurrentPassword.text?.clear()
                    binding.etNewPassword.text?.clear()
                    binding.etConfirmPassword.text?.clear()
                } else {
                    showMessage(response.body()?.message ?: "Erreur lors de la modification.", isError = true)
                }
            } catch (_: Exception) {
                showMessage("Erreur réseau. Réessayez.", isError = true)
            } finally {
                setLoading(false)
            }
        }
    }

    private fun showMessage(message: String, isError: Boolean) {
        binding.tvMessage.text = message
        binding.tvMessage.setTextColor(
            if (isError) getColor(bf.ujkz.schoolconnect.R.color.danger)
            else getColor(bf.ujkz.schoolconnect.R.color.success)
        )
        binding.tvMessage.visibility = View.VISIBLE
    }

    private fun hideMessage() {
        binding.tvMessage.visibility = View.GONE
    }

    private fun setLoading(loading: Boolean) {
        binding.progressBar.visibility = if (loading) View.VISIBLE else View.GONE
        binding.btnSubmit.isEnabled = !loading
    }
}
