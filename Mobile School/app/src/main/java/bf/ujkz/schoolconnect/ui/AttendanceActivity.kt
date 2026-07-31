package bf.ujkz.schoolconnect.ui

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.adapters.AttendanceAdapter
import bf.ujkz.schoolconnect.databinding.ActivityAttendanceBinding
import bf.ujkz.schoolconnect.network.RetrofitClient
import kotlinx.coroutines.launch

class AttendanceActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAttendanceBinding
    private var childId: Int = -1
    private lateinit var adapter: AttendanceAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAttendanceBinding.inflate(layoutInflater)
        setContentView(binding.root)

        childId = intent.getIntExtra("child_id", -1)
        val childName = intent.getStringExtra("child_name") ?: ""
        binding.toolbar.title = "Absences — $childName"
        binding.toolbar.setNavigationIcon(android.R.drawable.ic_menu_close_clear_cancel)
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        adapter = AttendanceAdapter()
        binding.recyclerAttendance.layoutManager = LinearLayoutManager(this)
        binding.recyclerAttendance.adapter = adapter

        loadSummary()
        loadAttendances()
    }

    private fun loadSummary() {
        if (childId == -1) return
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@AttendanceActivity)
                val response = api.getAttendanceSummary(childId)
                if (response.isSuccessful && response.body()?.success == true) {
                    val summary = response.body()?.data?.summary ?: return@launch
                    binding.tvPresentCount.text = summary.present.toString()
                    binding.tvAbsentCount.text = summary.absent.toString()
                    binding.tvLateCount.text = summary.late.toString()
                    binding.tvExcusedCount.text = summary.excused.toString()
                }
            } catch (_: Exception) {
                // géré par la liste
            }
        }
    }

    private fun loadAttendances() {
        if (childId == -1) return
        setLoading(true)
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@AttendanceActivity)
                val response = api.getAttendances(childId)
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
                setLoading(false)
            }
        }
    }

    private fun setLoading(loading: Boolean) {
        binding.progressBar.visibility = if (loading) View.VISIBLE else View.GONE
    }
}
