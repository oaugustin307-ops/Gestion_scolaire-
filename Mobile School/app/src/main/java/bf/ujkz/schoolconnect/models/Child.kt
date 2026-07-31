package bf.ujkz.schoolconnect.models

data class Child(
    val id: Int,
    val first_name: String,
    val last_name: String,
    val full_name: String,
    val photo: String?,
    val `class`: SchoolClass,
    val date_of_birth: String?,
    val gender: String?,
    val registration_date: String?
)

data class ChildDetails(
    val id: Int,
    val first_name: String,
    val last_name: String,
    val full_name: String,
    val photo: String?,
    val date_of_birth: String?,
    val gender: String?,
    val address: String?,
    val registration_date: String?,
    val `class`: SchoolClass
)
