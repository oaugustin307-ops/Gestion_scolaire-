package bf.ujkz.schoolconnect.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemChildBinding
import bf.ujkz.schoolconnect.models.Child
import com.bumptech.glide.Glide

class ChildAdapter(
    private val children: MutableList<Child> = mutableListOf(),
    private val onClick: (Child) -> Unit
) : RecyclerView.Adapter<ChildAdapter.ChildViewHolder>() {

    fun submitList(newList: List<Child>) {
        children.clear()
        children.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ChildViewHolder {
        val binding = ItemChildBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ChildViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ChildViewHolder, position: Int) {
        holder.bind(children[position])
    }

    override fun getItemCount(): Int = children.size

    inner class ChildViewHolder(private val binding: ItemChildBinding) :
        RecyclerView.ViewHolder(binding.root) {

        fun bind(child: Child) {
            binding.tvName.text = child.full_name
            binding.tvClass.text = "${child.`class`.name} • ${child.`class`.level}"

            if (!child.photo.isNullOrEmpty()) {
                Glide.with(binding.imgPhoto.context)
                    .load(child.photo)
                    .placeholder(android.R.drawable.sym_def_app_icon)
                    .into(binding.imgPhoto)
            }

            binding.root.setOnClickListener { onClick(child) }
        }
    }
}
