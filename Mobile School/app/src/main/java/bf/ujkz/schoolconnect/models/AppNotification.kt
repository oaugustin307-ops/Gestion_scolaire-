package bf.ujkz.schoolconnect.models

data class AppNotification(
    val id: Int,
    val title: String,
    val message: String,
    val type: String,
    val date: String,
    val priority: String
)
