package bf.ujkz.schoolconnect.models

data class GradeDetail(
    val id: Int,
    val grade: String,
    val trimester: Int,
    val remarks: String?,
    val subject: SubjectMini,
    val date: String
)

data class SubjectMini(
    val name: String,
    val coefficient: String
)

data class GradeRow(
    val subject: String,
    val coefficient: String,
    val grade: String,
    val remarks: String?
)

data class AllGradesData(
    val student: GradeStudentMini,
    val annual_average: Double,
    val trimesters: Map<String, TrimesterGrades>
)

data class GradeStudentMini(
    val id: Int,
    val full_name: String,
    val `class`: String
)

data class TrimesterGrades(
    val average: Double,
    val grades: List<GradeRow>
)

data class TrimesterGradesResponse(
    val trimester: Int,
    val average: Double,
    val grades: List<GradeRow>
)
