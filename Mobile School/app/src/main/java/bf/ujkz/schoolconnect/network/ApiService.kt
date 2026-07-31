package bf.ujkz.schoolconnect.network

import bf.ujkz.schoolconnect.models.*
import retrofit2.Response
import retrofit2.http.*

interface ApiService {

    // ---------- Authentification ----------
    @POST("guardian/login")
    suspend fun login(@Body request: LoginRequest): Response<ApiResponse<LoginData>>

    @POST("guardian/logout")
    suspend fun logout(): Response<ApiResponse<Unit>>

    @POST("guardian/change-password")
    suspend fun changePassword(@Body request: ChangePasswordRequest): Response<ApiResponse<Unit>>

    @GET("guardian/profile")
    suspend fun getProfile(): Response<ApiResponse<Guardian>>

    // ---------- Enfants ----------
    @GET("guardian/children")
    suspend fun getChildren(): Response<ApiResponse<List<Child>>>

    @GET("guardian/children/{id}")
    suspend fun getChildDetails(@Path("id") childId: Int): Response<ApiResponse<ChildDetails>>

    @GET("guardian/children/{id}/dashboard")
    suspend fun getDashboard(@Path("id") childId: Int): Response<ApiResponse<DashboardData>>

    // ---------- Notes ----------
    @GET("guardian/children/{id}/subjects")
    suspend fun getSubjects(@Path("id") childId: Int): Response<ApiResponse<List<Subject>>>

    @GET("guardian/children/{id}/grades")
    suspend fun getAllGrades(@Path("id") childId: Int): Response<ApiResponse<AllGradesData>>

    @GET("guardian/children/{id}/grades/{subjectId}")
    suspend fun getGradesBySubject(
        @Path("id") childId: Int,
        @Path("subjectId") subjectId: Int
    ): Response<ApiResponse<List<GradeDetail>>>

    @GET("guardian/children/{id}/grades/trimester/{trimester}")
    suspend fun getGradesByTrimester(
        @Path("id") childId: Int,
        @Path("trimester") trimester: Int
    ): Response<ApiResponse<TrimesterGradesResponse>>

    // ---------- Absences ----------
    @GET("guardian/children/{id}/attendances")
    suspend fun getAttendances(@Path("id") childId: Int): Response<ApiResponse<List<AttendanceRecord>>>

    @GET("guardian/children/{id}/attendances/summary")
    suspend fun getAttendanceSummary(@Path("id") childId: Int): Response<ApiResponse<AttendanceSummaryData>>

    // ---------- Notifications ----------
    @GET("guardian/notifications")
    suspend fun getNotifications(): Response<ApiResponse<List<AppNotification>>>

    @GET("guardian/notifications/unread")
    suspend fun getUnreadNotifications(): Response<ApiResponse<List<AppNotification>>>

    @POST("guardian/notifications/{id}/read")
    suspend fun markNotificationAsRead(@Path("id") notificationId: Int): Response<ApiResponse<Unit>>
}
