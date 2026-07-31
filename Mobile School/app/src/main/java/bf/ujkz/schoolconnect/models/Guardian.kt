package bf.ujkz.schoolconnect.models

data class Guardian(
    val id: Int,
    val first_name: String,
    val last_name: String,
    val email: String,
    val phone: String?,
    val children_count: Int? = null
)

data class LoginRequest(
    val email: String,
    val password: String
)

data class LoginData(
    val guardian: Guardian
)

data class ChangePasswordRequest(
    val current_password: String,
    val new_password: String,
    val new_password_confirmation: String
)
