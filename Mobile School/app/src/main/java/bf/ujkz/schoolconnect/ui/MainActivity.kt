package bf.ujkz.schoolconnect.ui

import android.content.Intent
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import android.view.View
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.R
import bf.ujkz.schoolconnect.adapters.ChildAdapter
import bf.ujkz.schoolconnect.databinding.ActivityMainBinding
import bf.ujkz.schoolconnect.models.Child
import bf.ujkz.schoolconnect.network.RetrofitClient
import bf.ujkz.schoolconnect.utils.SessionManager
import kotlinx.coroutines.launch

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding
    private lateinit var sessionManager: SessionManager
    private lateinit var adapter: ChildAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        sessionManager = SessionManager(this)
        binding.tvWelcome.text = "Bienvenue, ${sessionManager.getGuardianFullName()}"

        adapter = ChildAdapter { child -> openChildDashboard(child) }
        binding.recyclerChildren.layoutManager = LinearLayoutManager(this)
        binding.recyclerChildren.adapter = adapter

        binding.swipeRefresh.setOnRefreshListener { loadChildren() }

        // Annonces / notifications accessibles depuis le bandeau
        binding.tvWelcome.setOnLongClickListener {
            startActivity(Intent(this, NotificationsActivity::class.java))
            true
        }

        loadChildren()
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menuInflater.inflate(R.menu.menu_main, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        return when (item.itemId) {
            R.id.action_notifications -> {
                startActivity(Intent(this, NotificationsActivity::class.java))
                true
            }
            R.id.action_change_password -> {
                startActivity(Intent(this, ChangePasswordActivity::class.java))
                true
            }
            R.id.action_logout -> {
                confirmLogout()
                true
            }
            else -> super.onOptionsItemSelected(item)
        }
    }

    private fun loadChildren() {
        setLoading(true)
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@MainActivity)
                val response = api.getChildren()

                if (response.isSuccessful && response.body()?.success == true) {
                    val children: List<Child> = response.body()?.data ?: emptyList()
                    adapter.submitList(children)
                    binding.tvEmpty.visibility = if (children.isEmpty()) View.VISIBLE else View.GONE
                } else if (response.code() == 401) {
                    // Session expirée -> retour au login
                    sessionManager.clear()
                    RetrofitClient.clearSession()
                    startActivity(Intent(this@MainActivity, LoginActivity::class.java))
                    finish()
                }
            } catch (_: Exception) {
                binding.tvEmpty.text = "Erreur réseau. Tirez vers le bas pour réessayer."
                binding.tvEmpty.visibility = View.VISIBLE
            } finally {
                setLoading(false)
            }
        }
    }

    private fun openChildDashboard(child: Child) {
        val intent = Intent(this, ChildDashboardActivity::class.java)
        intent.putExtra("child_id", child.id)
        intent.putExtra("child_name", child.full_name)
        startActivity(intent)
    }

    private fun confirmLogout() {
        AlertDialog.Builder(this)
            .setTitle("Déconnexion")
            .setMessage("Voulez-vous vraiment vous déconnecter ?")
            .setPositiveButton("Déconnexion") { _, _ -> doLogout() }
            .setNegativeButton("Annuler", null)
            .show()
    }

    private fun doLogout() {
        lifecycleScope.launch {
            try {
                RetrofitClient.getApiService(this@MainActivity).logout()
            } catch (_: Exception) {
                // même en cas d'erreur réseau, on déconnecte localement
            } finally {
                sessionManager.clear()
                RetrofitClient.clearSession()
                startActivity(Intent(this@MainActivity, LoginActivity::class.java))
                finish()
            }
        }
    }

    private fun setLoading(loading: Boolean) {
        binding.progressBar.visibility = if (loading) View.VISIBLE else View.GONE
        binding.swipeRefresh.isRefreshing = false
    }
}
