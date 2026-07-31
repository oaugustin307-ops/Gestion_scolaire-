package bf.ujkz.schoolconnect.models

data class SchoolClass(
    val id: Int,
    val name: String,
    val level: String,
    val school_fees: String? = null
)
