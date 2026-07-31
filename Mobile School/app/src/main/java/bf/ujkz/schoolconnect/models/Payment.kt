package bf.ujkz.schoolconnect.models

data class Payment(
    val id: Int,
    val amount: String,
    val payment_date: String,
    val payment_method: String,
    val receipt_number: String,
    val remarks: String?,
    val status: String? = null // "validee", "en_attente", "rejetee"
)

data class PaymentRequest(
    val amount: Double,
    val payment_method: String,
    val remarks: String?
)

data class PaymentSummary(
    val student: PaymentStudentMini,
    val school_fees: String,
    val total_paid: Double,
    val remaining_balance: Double,
    val payment_percentage: Double
)

data class PaymentStudentMini(
    val id: Int,
    val full_name: String,
    val `class`: String
)

data class PaymentDetails(
    val id: Int,
    val amount: String,
    val payment_date: String,
    val payment_method: String,
    val receipt_number: String,
    val remarks: String?,
    val status: String? = null,
    val student: PaymentStudentName
)

data class PaymentStudentName(
    val full_name: String
)

data class ReceiptData(
    val receipt_url: String,
    val receipt_number: String
)
