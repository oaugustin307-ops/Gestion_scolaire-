package bf.ujkz.schoolconnect.network

import android.content.Context
import android.content.SharedPreferences
import okhttp3.Cookie
import okhttp3.CookieJar
import okhttp3.HttpUrl

/**
 * Laravel utilise l'authentification par SESSION (cookie), pas par token Bearer.
 * Ce CookieJar conserve donc le cookie de session retourné après /guardian/login
 * et le renvoie automatiquement sur chaque appel suivant (comme le ferait un navigateur).
 *
 * Les cookies sont aussi persistés dans les SharedPreferences pour survivre
 * à la fermeture de l'application (l'utilisateur reste connecté).
 */
class PersistentCookieJar(context: Context) : CookieJar {

    private val prefs: SharedPreferences =
        context.getSharedPreferences("cookie_prefs", Context.MODE_PRIVATE)

    private val cookieStore = mutableMapOf<String, MutableList<Cookie>>()

    init {
        // Recharge les cookies sauvegardés au démarrage de l'app
        val saved = prefs.getStringSet("cookies", emptySet()) ?: emptySet()
        for (entry in saved) {
            // format stocké : "host|name|value|domain|path|expiresAt|secure"
            val parts = entry.split("|")
            if (parts.size >= 7) {
                val host = parts[0]
                try {
                    val cookie = Cookie.Builder()
                        .name(parts[1])
                        .value(parts[2])
                        .domain(parts[3])
                        .path(parts[4])
                        .expiresAt(parts[5].toLong())
                        .apply { if (parts[6] == "1") secure() }
                        .build()
                    cookieStore.getOrPut(host) { mutableListOf() }.add(cookie)
                } catch (_: Exception) {
                    // cookie corrompu, on l'ignore
                }
            }
        }
    }

    override fun saveFromResponse(url: HttpUrl, cookies: List<Cookie>) {
        if (cookies.isEmpty()) return
        val host = url.host
        val existing = cookieStore.getOrPut(host) { mutableListOf() }
        for (cookie in cookies) {
            existing.removeAll { it.name == cookie.name }
            existing.add(cookie)
        }
        persist()
    }

    override fun loadForRequest(url: HttpUrl): List<Cookie> {
        val host = url.host
        val cookies = cookieStore[host] ?: return emptyList()
        val now = System.currentTimeMillis()
        // retire les cookies expirés
        cookies.removeAll { it.expiresAt < now }
        return cookies.toList()
    }

    private fun persist() {
        val all = mutableSetOf<String>()
        for ((host, cookies) in cookieStore) {
            for (cookie in cookies) {
                all.add(
                    "$host|${cookie.name}|${cookie.value}|${cookie.domain}|${cookie.path}|${cookie.expiresAt}|${if (cookie.secure) "1" else "0"}"
                )
            }
        }
        prefs.edit().putStringSet("cookies", all).apply()
    }

    /** Appelé lors de la déconnexion pour vider tous les cookies de session */
    fun clear() {
        cookieStore.clear()
        prefs.edit().remove("cookies").apply()
    }
}
