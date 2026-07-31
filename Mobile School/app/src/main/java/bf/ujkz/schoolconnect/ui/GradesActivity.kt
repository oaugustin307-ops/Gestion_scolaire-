package bf.ujkz.schoolconnect.ui

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.adapters.GradeRowAdapter
import bf.ujkz.schoolconnect.databinding.ActivityGradesBinding
import bf.ujkz.schoolconnect.network.RetrofitClient
import com.google.android.material.tabs.TabLayout
import kotlinx.coroutines.launch

class GradesActivity : AppCompatActivity() {

    private lateinit var binding: ActivityGradesBinding
    private var childId: Int = -1
    private lateinit var adapter: GradeRowAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityGradesBinding.inflate(layoutInflater)
        setContentView(binding.root)

        childId = intent.getIntExtra("child_id", -1)
        val childName = intent.getStringExtra("child_name") ?: ""

        binding.toolbar.title = "Notes — $childName"
        binding.toolbar.setNavigationIcon(android.R.drawable.ic_menu_close_clear_cancel)
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        adapter = GradeRowAdapter()
        binding.recyclerGrades.layoutManager = LinearLayoutManager(this)
        binding.recyclerGrades.adapter = adapter

        binding.tabLayout.addOnTabSelectedListener(object : TabLayout.OnTabSelectedListener {
            override fun onTabSelected(tab: TabLayout.Tab) {
                loadTrimester(tab.position + 1)
            }
            override fun onTabUnselected(tab: TabLayout.Tab) {}
            override fun onTabReselected(tab: TabLayout.Tab) {}
        })

        loadTrimester(1)
    }

    private fun loadTrimester(trimester: Int) {
        if (childId == -1) return
        setLoading(true)

        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@GradesActivity)
                val response = api.getGradesByTrimester(childId, trimester)

                if (response.isSuccessful && response.body()?.success == true) {
                    val data = response.body()?.data
                    val grades = data?.grades ?: emptyList()

                    binding.tvAverage.text = "Moyenne du trimestre : %.2f/20".format(data?.average ?: 0.0)
                    adapter.submitList(grades)
                    binding.tvEmpty.visibility = if (grades.isEmpty()) View.VISIBLE else View.GONE
                } else {
                    binding.tvAverage.text = ""
                    adapter.submitList(emptyList())
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
