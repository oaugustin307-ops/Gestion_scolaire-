package bf.ujkz.schoolconnect.adapters

import android.graphics.Color
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import bf.ujkz.schoolconnect.databinding.ItemPaymentBinding
import bf.ujkz.schoolconnect.models.Payment

class PaymentAdapter(
    private val items: MutableList<Payment> = mutableListOf()
) : RecyclerView.Adapter<PaymentAdapter.ViewHolder>() {

    fun submitList(newList: List<Payment>) {
        items.clear()
        items.addAll(newList)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, position: Int): ViewHolder {
        val binding = ItemPaymentBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class ViewHolder(private val binding: ItemPaymentBinding) :
        RecyclerView.ViewHolder(binding.root) {

        fun bind(item: Payment) {
            binding.tvAmount.text = "${item.amount} FCFA"
            binding.tvMethod.text = item.payment_method
            binding.tvReceipt.text = "Reçu n° ${item.receipt_number}"
            binding.tvDate.text = item.payment_date

            // Badge de statut : couleur différente selon la validation de l'école
            when (item.status) {
                "en_attente" -> {
                    binding.tvStatus.text = "En attente"
                    binding.tvStatus.setBackgroundColor(Color.parseColor("#ED6C02"))
                }
                "rejetee" -> {
                    binding.tvStatus.text = "Rejeté"
                    binding.tvStatus.setBackgroundColor(Color.parseColor("#C62828"))
                }
                else -> {
                    // "validee" ou null (anciens paiements sans statut)
                    binding.tvStatus.text = "Validé"
                    binding.tvStatus.setBackgroundColor(Color.parseColor("#2E7D32"))
                }
            }
        }
    }
}
