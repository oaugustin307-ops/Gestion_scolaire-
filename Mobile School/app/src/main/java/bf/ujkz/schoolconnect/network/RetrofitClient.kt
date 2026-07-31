package bf.ujkz.schoolconnect.network

import android.content.Context
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.concurrent.TimeUnit

object RetrofitClient {

    /**
     * IMPORTANT — Adresse de l'API :
     * - Émulateur Android Studio  -> 10.0.2.2 (alias de "localhost" de la machine hôte)
     * - Téléphone physique (même réseau Wi-Fi que le PC) -> remplace par l'IP locale
     *   de ta machine, ex: "http://192.168.1.42:8000/api/"
     * - `php artisan serve` doit tourner sur le PC pour que l'API soit accessible.
     */
    private const val BASE_URL = "http://10.0.2.2:8000/api/"

    @Volatile
    private var retrofit: Retrofit? = null

    @Volatile
    private var cookieJar: PersistentCookieJar? = null

    fun getInstance(context: Context): Retrofit {
        return retrofit ?: synchronized(this) {
            retrofit ?: buildRetrofit(context.applicationContext).also { retrofit = it }
        }
    }

    fun getApiService(context: Context): ApiService =
        getInstance(context).create(ApiService::class.java)

    /** Permet de vider les cookies lors de la déconnexion */
    fun clearSession() {
        cookieJar?.clear()
    }

    private fun buildRetrofit(context: Context): Retrofit {
        val jar = PersistentCookieJar(context)
        cookieJar = jar

        val logging = HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }

        val client = OkHttpClient.Builder()
            .cookieJar(jar)
            .addInterceptor(logging)
            .connectTimeout(20, TimeUnit.SECONDS)
            .readTimeout(20, TimeUnit.SECONDS)
            .writeTimeout(20, TimeUnit.SECONDS)
            .build()

        return Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }
}
