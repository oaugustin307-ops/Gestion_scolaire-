package bf.ujkz.schoolconnect.utils

import android.content.Context
import android.content.SharedPreferences
import bf.ujkz.schoolconnect.models.Guardian

/**
 * Stocke localement les infos du parent connecté (PAS le mot de passe).
 * La session "réelle" côté serveur est gérée par le cookie (PersistentCookieJar) ;
 * ceci ne sert qu'à savoir, côté UI, si l'utilisateur est "déjà connecté"
 * et à afficher son nom sans refaire d'appel réseau.
 */
class SessionManager(context: Context) {

    private val prefs: SharedPreferences =
        context.getSharedPreferences("session_prefs", Context.MODE_PRIVATE)

    fun saveGuardian(guardian: Guardian) {
        prefs.edit()
            .putBoolean(KEY_LOGGED_IN, true)
            .putInt(KEY_ID, guardian.id)
            .putString(KEY_FIRST_NAME, guardian.first_name)
            .putString(KEY_LAST_NAME, guardian.last_name)
            .putString(KEY_EMAIL, guardian.email)
            .apply()
    }

    fun isLoggedIn(): Boolean = prefs.getBoolean(KEY_LOGGED_IN, false)

    fun getGuardianFullName(): String {
        val first = prefs.getString(KEY_FIRST_NAME, "") ?: ""
        val last = prefs.getString(KEY_LAST_NAME, "") ?: ""
        return "$first $last".trim()
    }

    fun getGuardianEmail(): String = prefs.getString(KEY_EMAIL, "") ?: ""

    fun clear() {
        prefs.edit().clear().apply()
    }

    companion object {
        private const val KEY_LOGGED_IN = "logged_in"
        private const val KEY_ID = "guardian_id"
        private const val KEY_FIRST_NAME = "first_name"
        private const val KEY_LAST_NAME = "last_name"
        private const val KEY_EMAIL = "email"
    }
}
