package bf.ujkz.schoolconnect.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.adapters.RecentGradeAdapter
import bf.ujkz.schoolconnect.databinding.ActivityChildDashboardBinding
import bf.ujkz.schoolconnect.network.RetrofitClient
import com.bumptech.glide.Glide
import kotlinx.coroutines.launch

class ChildDashboardActivity : AppCompatActivity() {

    private lateinit var binding: ActivityChildDashboardBinding
    private var childId: Int = -1
    private lateinit var recentGradeAdapter: RecentGradeAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityChildDashboardBinding.inflate(layoutInflater)
        setContentView(binding.root)

        childId = intent.getIntExtra("child_id", -1)
        val childName = intent.getStringExtra("child_name") ?: "Élève"

        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.title = childName
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        recentGradeAdapter = RecentGradeAdapter()
        binding.recyclerRecentGrades.layoutManager = LinearLayoutManager(this)
        binding.recyclerRecentGrades.adapter = recentGradeAdapter

        binding.btnGrades.setOnClickListener { openWithChildId(GradesActivity::class.java) }
        binding.btnAttendance.setOnClickListener { openWithChildId(AttendanceActivity::class.java) }

        loadDashboard()
    }

    private fun openWithChildId(activity: Class<*>) {
        val intent = Intent(this, activity)
        intent.putExtra("child_id", childId)
        intent.putExtra("child_name", binding.toolbar.title)
        startActivity(intent)
    }

    private fun loadDashboard() {
        if (childId == -1) return

        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@ChildDashboardActivity)
                val response = api.getDashboard(childId)

                if (response.isSuccessful && response.body()?.success == true) {
                    val data = response.body()?.data ?: return@launch

                    binding.tvName.text = data.student.full_name
                    binding.tvClass.text = "${data.student.`class`.name} • ${data.student.`class`.level}"
                    binding.tvAnnualAverage.text = "%.2f/20".format(data.averages.annual)
                    binding.tvRank.text = "${data.rank.position}${ordinalSuffix(data.rank.position)} / ${data.rank.total}"

                    if (!data.student.photo.isNullOrEmpty()) {
                        Glide.with(this@ChildDashboardActivity)
                            .load(data.student.photo)
                            .placeholder(android.R.drawable.sym_def_app_icon)
                            .into(binding.imgPhoto)
                    }

                    if (data.recent_grades.isEmpty()) {
                        binding.tvNoRecentGrades.visibility = View.VISIBLE
                    } else {
                        recentGradeAdapter.submitList(data.recent_grades)
                    }

                    binding.contentLayout.visibility = View.VISIBLE
                }
            } catch (_: Exception) {
                // En cas d'erreur réseau, on garde l'écran de chargement masqué
            } finally {
                binding.progressBar.visibility = View.GONE
            }
        }
    }

    private fun ordinalSuffix(n: Int): String = if (n == 1) "er" else "e"
}
