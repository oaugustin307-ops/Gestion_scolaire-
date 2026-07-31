package bf.ujkz.schoolconnect.adapters

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemRecentGradeBinding
import bf.ujkz.schoolconnect.models.RecentGrade

class RecentGradeAdapter(
    private val items: MutableList<RecentGrade> = mutableListOf()
) : RecyclerView.Adapter<RecentGradeAdapter.ViewHolder>() {

    fun submitList(newList: List<RecentGrade>) {
        items.clear()
        items.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ViewHolder {
        val binding = ItemRecentGradeBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class ViewHolder(private val binding: ItemRecentGradeBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(item: RecentGrade) {
            binding.tvSubject.text = item.subject
            binding.tvMeta.text = "Trimestre ${item.trimester} • ${item.date}"
            binding.tvGrade.text = "${item.grade}/20"
        }
    }
}
