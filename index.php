<?php
require_once 'modules/session.php'; 
?>
<?php include_once("templates/header.php"); ?>
<?php include_once("templates/navbar.php"); ?>





<!-- Main Content -->
<div class="container mt-3">
    <div class="tab-content">
        <!-- Today's Timeline Tab -->
        <div class="tab-pane fade show active" id="today">
            <!-- Stats Grid -->
            <div class="stats-grid mb-4">
                <div class="stat-card animate-in">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-value" id="totalTasks">12</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card animate-in" style="animation-delay: 0.1s;">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value" id="totalHours">8.5h</div>
                    <div class="stat-label">Hours Today</div>
                </div>
                <div class="stat-card animate-in" style="animation-delay: 0.2s;">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value" id="completedTasks">8</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card animate-in" style="animation-delay: 0.3s;">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-value" id="productivity">87%</div>
                    <div class="stat-label">Productivity</div>
                </div>
            </div>

            <div class="glass-card p-4 animate-in" style="animation-delay: 0.4s;">
                <h4 class="mb-4 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-timeline me-2"></i>Today's Activities</span>
                    <span class="badge bg-primary" id="currentDate"></span>
                </h4>

                <div class="timeline" id="timelineContainer">
                    <!-- Timeline items will be dynamically loaded here -->
                </div>
            </div>
        </div>

        <!-- Calendar Tab -->
        <div class="tab-pane fade" id="calendar-view">
            <div id="calendar" class="animate-in"></div>
        </div>

        <!-- Reports Tab -->
        <div class="tab-pane fade" id="reports">
            <h4 class="mb-4"><i class="fas fa-chart-bar me-2"></i>Activity Reports</h4>

            <div class="glass-card mb-4 animate-in">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Filter Options</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" id="dateRange">
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="quarter">Last 3 Months</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Group By</label>
                            <select class="form-select" id="groupBy">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="location">Location</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary d-block w-100" id="generateReport">
                                <i class="fas fa-magic me-2"></i>Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card animate-in" style="animation-delay: 0.1s;">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="mb-0 text-white"><i class="fas fa-chart-pie me-2"></i>Report Results</h5>
                </div>
                <div class="card-body p-4">
                    <div id="reportResults">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                            <p>Select filter options and generate a report to see insights</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Activity Button -->
<a href="#" class="btn-floating" data-bs-toggle="modal" data-bs-target="#addActivityModal">
    <i class="fas fa-plus"></i>
</a>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="activityForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="projectName" class="form-label">
                                    <i class="fas fa-project-diagram me-1"></i>Project Name
                                </label>
                                <input type="text" class="form-control" id="projectName" required
                                    placeholder="Enter project name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activityDate" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Date
                                </label>
                                <input type="date" class="form-control" id="activityDate" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskName" class="form-label">
                                    <i class="fas fa-tasks me-1"></i>Task
                                </label>
                                <select class="form-select" id="taskName" required>
                                    <option value="">Select a task</option>
                                    <option value="Development">Development</option>
                                    <option value="Testing">Testing</option>
                                    <option value="Design">Design</option>
                                    <option value="Documentation">Documentation</option>
                                    <option value="Meeting">Meeting</option>
                                    <option value="Review">Review</option>
                                    <option value="Research">Research</option>
                                    <option value="Planning">Planning</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subtaskName" class="form-label">
                                    <i class="fas fa-list me-1"></i>Subtask
                                </label>
                                <select class="form-select" id="subtaskName">
                                    <option value="">Select a subtask</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="startTime" class="form-label">
                                    <i class="fas fa-play me-1"></i>Start Time
                                </label>
                                <input type="time" class="form-control" id="startTime" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="endTime" class="form-label">
                                    <i class="fas fa-stop me-1"></i>End Time
                                </label>
                                <input type="time" class="form-control" id="endTime" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">
                            <i class="fas fa-flag me-1"></i>Status
                        </label>
                        <select class="form-select" id="status" required>
                            <option value="">Select status</option>
                            <option value="future">Future</option>
                            <option value="current">Current</option>
                            <option value="doing">Doing</option>
                            <option value="done">Done</option>
                            <option value="in-progress">In Progress</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes

                        </label>
                        <textarea class="form-control" id="notes" rows="3"
                            placeholder="Add any additional notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveActivity">Save Activity</button>
            </div>
        </div>
    </div>
</div>

<!-- Activity Details Modal -->
<div class="modal fade" id="activityDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="activityDetailsContent">
                <!-- Details will be populated dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteActivity">Delete</button>
                <button type="button" class="btn btn-primary" id="editActivity">Edit</button>
            </div>
        </div>
    </div>
</div>
<?php include_once("templates/footer.php"); ?>