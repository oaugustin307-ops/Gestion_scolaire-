package bf.ujkz.schoolconnect.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemGradeRowBinding
import bf.ujkz.schoolconnect.models.GradeRow

class GradeRowAdapter(
    private val items: MutableList<GradeRow> = mutableListOf()
) : RecyclerView.Adapter<GradeRowAdapter.ViewHolder>() {

    fun submitList(newList: List<GradeRow>) {
        items.clear()
        items.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ViewHolder {
        val binding = ItemGradeRowBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class ViewHolder(private val binding: ItemGradeRowBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(item: GradeRow) {
            binding.tvSubject.text = item.subject
            binding.tvCoefficient.text = "Coefficient ${item.coefficient}"
            binding.tvGrade.text = "${item.grade}/20"
            if (!item.remarks.isNullOrEmpty()) {
                binding.tvRemarks.text = item.remarks
                binding.tvRemarks.visibility = View.VISIBLE
            } else {
                binding.tvRemarks.visibility = View.GONE
            }
        }
    }
}
