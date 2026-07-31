package bf.ujkz.schoolconnect.models

data class DashboardData(
    val student: DashboardStudent,
    val averages: Averages,
    val rank: Rank,
    val recent_grades: List<RecentGrade>
)

data class DashboardStudent(
    val id: Int,
    val first_name: String,
    val last_name: String,
    val full_name: String,
    val photo: String?,
    val `class`: SchoolClass
)

data class Averages(
    val trimester1: Double,
    val trimester2: Double,
    val trimester3: Double,
    val annual: Double
)

data class Rank(
    val position: Int,
    val total: Int
)

data class RecentGrade(
    val subject: String,
    val grade: String,
    val trimester: Int,
    val date: String
)
