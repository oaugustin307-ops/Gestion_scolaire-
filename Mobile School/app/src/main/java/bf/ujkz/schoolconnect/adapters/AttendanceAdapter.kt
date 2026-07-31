package bf.ujkz.schoolconnect.adapters

import android.graphics.Color
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemAttendanceBinding
import bf.ujkz.schoolconnect.models.AttendanceRecord

class AttendanceAdapter(
    private val items: MutableList<AttendanceRecord> = mutableListOf()
) : RecyclerView.Adapter<AttendanceAdapter.ViewHolder>() {

    fun submitList(newList: List<AttendanceRecord>) {
        items.clear()
        items.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ViewHolder {
        val binding = ItemAttendanceBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class ViewHolder(private val binding: ItemAttendanceBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(item: AttendanceRecord) {
            binding.tvDate.text = item.date
            binding.tvStatus.text = labelFor(item.status)
            binding.viewStatusDot.setBackgroundColor(colorFor(item.status))

            val reasonText = item.reason ?: item.remarks
            if (!reasonText.isNullOrEmpty()) {
                binding.tvReason.text = reasonText
                binding.tvReason.visibility = View.VISIBLE
            } else {
                binding.tvReason.visibility = View.GONE
            }
        }

        private fun labelFor(status: String): String = when (status) {
            "absent" -> "Absence"
            "late" -> "Retard"
            "excused" -> "Absence excusée"
            else -> status
        }

        private fun colorFor(status: String): Int = when (status) {
            "absent" -> Color.parseColor("#C62828")
            "late" -> Color.parseColor("#ED6C02")
            "excused" -> Color.parseColor("#6E7681")
            else -> Color.GRAY
        }
    }
}
