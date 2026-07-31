package bf.ujkz.schoolconnect.ui

import android.os.Bundle
import android.view.View
import android.widget.ArrayAdapter
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import bf.ujkz.schoolconnect.R
import bf.ujkz.schoolconnect.adapters.PaymentAdapter
import bf.ujkz.schoolconnect.databinding.ActivityPaymentsBinding
import bf.ujkz.schoolconnect.databinding.DialogDeclarePaymentBinding
import bf.ujkz.schoolconnect.models.PaymentRequest
import bf.ujkz.schoolconnect.network.RetrofitClient
import kotlinx.coroutines.launch

class PaymentsActivity : AppCompatActivity() {

    private lateinit var binding: ActivityPaymentsBinding
    private var childId: Int = -1
    private lateinit var adapter: PaymentAdapter

    // Méthodes de paiement proposées au parent
    private val paymentMethods = listOf(
        "Orange Money",
        "Moov Money",
        "Espèces",
        "Virement bancaire",
        "Autre"
    )

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPaymentsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        childId = intent.getIntExtra("child_id", -1)
        val childName = intent.getStringExtra("child_name") ?: ""

        binding.toolbar.title = "Paiements — $childName"
        binding.toolbar.setNavigationIcon(android.R.drawable.ic_menu_close_clear_cancel)
        binding.toolbar.setNavigationOnClickListener { onBackPressedDispatcher.onBackPressed() }

        adapter = PaymentAdapter()
        binding.recyclerPayments.layoutManager = LinearLayoutManager(this)
        binding.recyclerPayments.adapter = adapter

        // Bouton pour ouvrir le dialogue de déclaration de paiement
        binding.btnDeclarePayment.setOnClickListener { showDeclarePaymentDialog() }

        loadSummary()
        loadPayments()
    }

    /**
     * Affiche un dialogue permettant au parent de déclarer un paiement.
     * Le paiement sera créé avec le statut "en_attente" côté Laravel,
     * en attendant la validation par l'école via l'interface web.
     */
    private fun showDeclarePaymentDialog() {
        val dialogBinding = DialogDeclarePaymentBinding.inflate(layoutInflater)

        // Remplis la liste déroulante des méthodes de paiement
        val methodAdapter = ArrayAdapter(
            this,
            android.R.layout.simple_dropdown_item_1line,
            paymentMethods
        )
        dialogBinding.etPaymentMethod.setAdapter(methodAdapter)
        dialogBinding.etPaymentMethod.setText(paymentMethods[0], false) // Orange Money par défaut

        val dialog = AlertDialog.Builder(this)
            .setView(dialogBinding.root)
            .setCancelable(true)
            .create()

        dialogBinding.btnCancel.setOnClickListener { dialog.dismiss() }

        dialogBinding.btnConfirm.setOnClickListener {
            val amountStr = dialogBinding.etAmount.text?.toString()?.trim().orEmpty()
            val method = dialogBinding.etPaymentMethod.text?.toString()?.trim().orEmpty()
            val remarks = dialogBinding.etRemarks.text?.toString()?.trim()

            // Validation du formulaire
            if (amountStr.isEmpty()) {
                dialogBinding.tvDialogError.text = "Veuillez entrer un montant."
                dialogBinding.tvDialogError.visibility = View.VISIBLE
                return@setOnClickListener
            }

            val amount = amountStr.toDoubleOrNull()
            if (amount == null || amount <= 0) {
                dialogBinding.tvDialogError.text = "Montant invalide."
                dialogBinding.tvDialogError.visibility = View.VISIBLE
                return@setOnClickListener
            }

            if (method.isEmpty()) {
                dialogBinding.tvDialogError.text = "Veuillez choisir une méthode de paiement."
                dialogBinding.tvDialogError.visibility = View.VISIBLE
                return@setOnClickListener
            }

            // Désactive le bouton pendant l'envoi pour éviter les doubles soumissions
            dialogBinding.btnConfirm.isEnabled = false
            dialogBinding.tvDialogError.visibility = View.GONE

            lifecycleScope.launch {
                try {
                    val api = RetrofitClient.getApiService(this@PaymentsActivity)
                    val response = api.declarePayment(
                        childId,
                        PaymentRequest(amount, method, remarks)
                    )

                    if (response.isSuccessful && response.body()?.success == true) {
                        dialog.dismiss()
                        // Affiche un message de confirmation et recharge la liste
                        AlertDialog.Builder(this@PaymentsActivity)
                            .setTitle("Paiement déclaré ✓")
                            .setMessage("Votre paiement a été enregistré avec succès et sera vérifié par l'école avant validation officielle.")
                            .setPositiveButton("OK") { _, _ ->
                                // Recharge la liste et le résumé
                                loadSummary()
                                loadPayments()
                            }
                            .show()
                    } else {
                        dialogBinding.tvDialogError.text =
                            response.body()?.message ?: "Erreur lors de l'envoi. Réessayez."
                        dialogBinding.tvDialogError.visibility = View.VISIBLE
                        dialogBinding.btnConfirm.isEnabled = true
                    }
                } catch (_: Exception) {
                    dialogBinding.tvDialogError.text = "Erreur réseau. Vérifiez votre connexion."
                    dialogBinding.tvDialogError.visibility = View.VISIBLE
                    dialogBinding.btnConfirm.isEnabled = true
                }
            }
        }

        dialog.show()
    }

    private fun loadSummary() {
        if (childId == -1) return
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@PaymentsActivity)
                val response = api.getPaymentSummary(childId)
                if (response.isSuccessful && response.body()?.success == true) {
                    val data = response.body()?.data ?: return@launch
                    binding.tvSchoolFees.text = "${data.school_fees} FCFA"
                    binding.tvTotalPaid.text = "${data.total_paid} FCFA"
                    binding.tvRemaining.text = "${data.remaining_balance} FCFA"
                    binding.progressPercentage.progress =
                        data.payment_percentage.toInt().coerceIn(0, 100)
                    binding.tvPercentage.text = "${data.payment_percentage}% payé (validé)"
                }
            } catch (_: Exception) {
                // géré au niveau de la liste
            }
        }
    }

    private fun loadPayments() {
        if (childId == -1) return
        setLoading(true)
        lifecycleScope.launch {
            try {
                val api = RetrofitClient.getApiService(this@PaymentsActivity)
                val response = api.getPayments(childId)
                if (response.isSuccessful && response.body()?.success == true) {
                    val payments = response.body()?.data ?: emptyList()
                    adapter.submitList(payments)
                    binding.tvEmpty.visibility =
                        if (payments.isEmpty()) View.VISIBLE else View.GONE
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
