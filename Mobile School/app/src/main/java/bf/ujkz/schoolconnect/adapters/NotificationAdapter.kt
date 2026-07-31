package bf.ujkz.schoolconnect.adapters

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemNotificationBinding
import bf.ujkz.schoolconnect.models.AppNotification

class NotificationAdapter(
    private val items: MutableList<AppNotification> = mutableListOf()
) : RecyclerView.Adapter<NotificationAdapter.ViewHolder>() {

    fun submitList(newList: List<AppNotification>) {
        items.clear()
        items.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ViewHolder {
        val binding = ItemNotificationBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class ViewHolder(private val binding: ItemNotificationBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(item: AppNotification) {
            binding.tvTitle.text = item.title
            binding.tvMessage.text = item.message
            binding.tvDate.text = item.date

            when (item.priority) {
                "high" -> {
                    binding.tvPriority.text = "Important"
                    binding.tvPriority.setBackgroundColor(0xFFC62828.toInt())
                }
                "medium" -> {
                    binding.tvPriority.text = "Moyen"
                    binding.tvPriority.setBackgroundColor(0xFFED6C02.toInt())
                }
                else -> {
                    binding.tvPriority.text = "Info"
                    binding.tvPriority.setBackgroundColor(0xFF6E7681.toInt())
                }
            }
        }
    }
}
