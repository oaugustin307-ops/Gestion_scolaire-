package bf.ujkz.schoolconnect.models

data class AttendanceRecord(
    val id: Int,
    val date: String,
    val status: String,
    val reason: String?,
    val remarks: String?
)

data class AttendanceSummaryData(
    val student: AttendanceStudentMini,
    val summary: AttendanceCounts
)

data class AttendanceStudentMini(
    val id: Int,
    val full_name: String
)

data class AttendanceCounts(
    val total_days: Int,
    val present: Int,
    val absent: Int,
    val late: Int,
    val excused: Int
)
