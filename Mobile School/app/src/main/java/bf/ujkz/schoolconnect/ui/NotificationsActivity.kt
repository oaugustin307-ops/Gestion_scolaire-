package bf.ujkz.schoolconnect.ui

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.adapters.NotificationAdapter
import bf.ujkz.schoolconnect.databinding.ActivityNotificationsBinding
import bf.ujkz.schoolconnect.network.RetrofitClient
import kotlinx.coroutines.launch

class NotificationsActivity : AppCompatActivity() {

    private lateinit var binding: ActivityNotificationsBinding
    private lateinit var adapter: NotificationAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityNotificationsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.toolbar.setNavigationIcon(android.R.drawable.ic_menu_close_clear_cancel)
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        adapter = NotificationAdapter()
        binding.recyclerNotifications.layoutManager = LinearLayoutManager(this)
        binding.recyclerNotifications.adapter = adapter

        loadNotifications()
    }

    private fun loadNotifications() {
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@NotificationsActivity)
                val response = api.getNotifications()
                if (response.isSuccessful && response.body()?.success == true) {
                    val list = response.body()?.data ?: emptyList()
                    adapter.submitList(list)
                    binding.tvEmpty.visibility = if (list.isEmpty()) View.VISIBLE else View.GONE
                } else {
                    binding.tvEmpty.visibility = View.VISIBLE
                }
            } catch (_: Exception) {
                binding.tvEmpty.text = "Erreur réseau."
                binding.tvEmpty.visibility = View.VISIBLE
            } finally {
                binding.progressBar.visibility = View.GONE
            }
        }
    }
}
