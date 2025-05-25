// Global variables
let activities = [];
let taskSubtasks = {};
let selectedActivityId = null;
let calendar = null;
var currentUserId = null;


// Initialize user ID from the DOM
function initializeUserId() {
    const userSpan = document.getElementById('userId');
    currentUserId = userSpan ? userSpan.dataset.id : null;
}


// API helper functions
function makeAjaxCall(url, method = 'GET', data = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            method: method,
            data: data,
            contentType: method === 'GET' ? undefined : 'application/json',
            dataType: 'json',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error);
                console.log(error);
                reject(xhr.responseJSON || { error: error });
            }
        });
    });
}

// Load activities from server
async function loadActivities() {
    try {
        const response = await makeAjaxCall(`modules/get_data.php?action=get_tasks&user_id=${currentUserId}`);
         activities = response || [];
        console.log('Loaded activities:', activities);

        // Today's date
        const today = new Date().toISOString().split('T')[0];

        let totalTasks = activities.length;
        let completedTasks = 0;
        let hoursToday = 0;

        activities.forEach(task => {
            // Count completed tasks
            if (task.status === 'done') {
                completedTasks++;
            }

            // Calculate total time for today's tasks
            if (task.date === today) {
                const start = parseTime(task.startTime);
                const end = parseTime(task.endTime);
                const duration = (end - start) / (1000 * 60 * 60); // Convert ms to hours
                if (!isNaN(duration)) {
                    hoursToday += duration;
                }
            }
        });

        // Productivity as percentage
        const productivity = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

        // Update DOM
        document.getElementById('totalTasks').textContent = totalTasks;
        document.getElementById('totalHours').textContent = hoursToday.toFixed(1) + 'h';
        document.getElementById('completedTasks').textContent = completedTasks;
        document.getElementById('productivity').textContent = productivity + '%';

    } catch (error) {
        console.error('Error loading activities:', error);
        showErrorMessage('Failed to load activities. Please try again.');
    }
}

// Helper function to parse "HH:mm" into Date object (time only)
function parseTime(timeStr) {
    const [hours, minutes] = timeStr.split(':').map(Number);
    const now = new Date();
    now.setHours(hours, minutes, 0, 0);
    return now;
}


// Load subtasks mapping from server
async function loadSubtasks() {
    try {
        const response = await makeAjaxCall('modules/get_data.php?action=get_subtasks');
        taskSubtasks = response || {};
        console.log('Loaded subtasks:', taskSubtasks);
    } catch (error) {
        console.error('Error loading subtasks:', error);
        showErrorMessage('Failed to load subtasks. Using default values.');
        // Fallback to default subtasks
        taskSubtasks = {
            "Development": ["Frontend", "Backend", "Database", "API Integration", "Bug Fixing", "Code Review"],
            "Testing": ["Unit Testing", "Integration Testing", "User Testing", "Performance Testing", "Bug Testing"],
            "Design": ["UI Design", "UX Design", "Prototyping", "Wireframing", "Asset Creation"],
            "Documentation": ["Technical Documentation", "User Manual", "API Documentation", "Project Documentation"],
            "Meeting": ["Team Meeting", "Client Meeting", "Standup", "Review Meeting", "Planning Meeting"],
            "Review": ["Code Review", "Design Review", "Document Review", "Project Review"],
            "Research": ["Technology Research", "Market Research", "Competitor Analysis", "Feasibility Study"],
            "Planning": ["Sprint Planning", "Project Planning", "Task Planning", "Resource Planning"]
        };
    }
}

// Save activity to server
async function saveActivityToServer(activityData) {
    try {
        const response = await makeAjaxCall('modules/get_data.php?action=save_task', 'POST', JSON.stringify(activityData));
        return response;
    } catch (error) {
        console.error('Error saving activity:', error);
        throw error;
    }
}

// Delete activity from server
async function deleteActivityFromServer(activityId) {
    try {
        const response = await makeAjaxCall(`modules/get_data.php?action=delete_task&task_id=${activityId}&user_id=${currentUserId}`, 'DELETE');
        return response;
    } catch (error) {
        console.error('Error deleting activity:', error);
        throw error;
    }
}

// Show error message to user
function showErrorMessage(message) {
    // You can customize this to show errors in your preferred way
    alert('Error: ' + message);
}

// Show success message to user
function showSuccessMessage(message) {
    // You can customize this to show success messages in your preferred way
    console.log('Success: ' + message);
}

// Format time for display (12-hour format)
function formatTime(time24) {
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours);
    const suffix = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
    return `${hour12}:${minutes} ${suffix}`;
}

// Get status badge HTML
function getStatusBadge(status) {
    const statusConfig = {
        'future': { class: 'status-future', text: 'Future' },
        'current': { class: 'status-current', text: 'Current' },
        'doing': { class: 'status-doing', text: 'Doing' },
        'done': { class: 'status-done', text: 'Done' },
        'in-progress': { class: 'status-in-progress', text: 'In Progress' }
    };
    
    const config = statusConfig[status] || { class: 'bg-secondary', text: status };
    return `<span class="badge ${config.class} status-badge">${config.text}</span>`;
}

// Calculate duration between two times
function calculateDuration(startTime, endTime) {
    const start = new Date(`2000-01-01T${startTime}:00`);
    const end = new Date(`2000-01-01T${endTime}:00`);
    const diff = end - start;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    return hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
}

// Display today's activities in timeline
function displayTodayActivities() {
    const timelineEl = $('.timeline');
    timelineEl.empty();
    
    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];
    
    // Filter activities for today
    const todayActivities = activities.filter(act => act.date === today);
    
    if (todayActivities.length === 0) {
        timelineEl.html('<p class="text-center text-muted">No activities recorded for today</p>');
        return;
    }
    
    // Sort by start time
    todayActivities.sort((a, b) => a.startTime.localeCompare(b.startTime));
    
    // Create timeline items
    todayActivities.forEach(activity => {
        const duration = calculateDuration(activity.startTime, activity.endTime);
        const timelineItem = `
            <div class="timeline-item" data-id="${activity.id}">
                <div class="timeline-dot"></div>
                <div class="time-badge">${formatTime(activity.startTime)} - ${formatTime(activity.endTime)}</div>
                <div class="location">${activity.projectName}</div>
                <div class="details">
                    <strong>${activity.taskName}</strong>
                    ${activity.subtaskName ? ` · ${activity.subtaskName}` : ''}
                    <br>
                    <small class="text-muted">Duration: ${duration}</small>
                    ${getStatusBadge(activity.status)}
                    <br>
                    <small class="text-muted">approved:  ${getStatusBadge(activity.approved)}</small>
                    ${activity.notes ? `<br><small class="text-muted"><i class="fas fa-sticky-note"></i> ${activity.notes}</small>` : ''}
                </div>
            </div>
        `;
        
        timelineEl.append(timelineItem);
    });
    
    // Add click event to view details
    $('.timeline-item').click(function() {
        const activityId = $(this).data('id');
        showActivityDetails(activityId);
    });
}

// Initialize FullCalendar
function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: getCalendarEvents(),
        eventClick: function(info) {
            const activityId = info.event.id;
            showActivityDetails(activityId);
        }
    });
    
    calendar.render();
}

// Convert activities to calendar events
function getCalendarEvents() {
    return activities.map(activity => {
        const eventDate = activity.date;
        const statusColors = {
            'future': '#17a2b8',
            'current': '#ffc107',
            'doing': '#fd7e14',
            'done': '#28a745',
            'in-progress': '#6f42c1'
        };
        
        return {
            id: activity.id,
            title: `${activity.projectName} - ${activity.taskName}`,
            start: `${eventDate}T${activity.startTime}:00`,
            end: `${eventDate}T${activity.endTime}:00`,
            backgroundColor: statusColors[activity.status] || '#1a73e8',
            borderColor: statusColors[activity.status] || '#1a73e8'
        };
    });
}

// Show activity details in modal
function showActivityDetails(activityId) {
    const activity = activities.find(act => act.id === activityId);
    if (!activity) return;
    
    selectedActivityId = activityId;
    
    const duration = calculateDuration(activity.startTime, activity.endTime);
    const detailsContent = `
        <div class="mb-3">
            <strong>Project:</strong> ${activity.projectName}
        </div>
        <div class="mb-3">
            <strong>Task:</strong> ${activity.taskName}
            ${activity.subtaskName ? ` - ${activity.subtaskName}` : ''}
        </div>
        <div class="mb-3">
            <strong>Time:</strong> ${formatTime(activity.startTime)} - ${formatTime(activity.endTime)}
            <br><small class="text-muted">Duration: ${duration}</small>
        </div>
        <div class="mb-3">
            <strong>Status:</strong> ${getStatusBadge(activity.status)}
        </div>
        <div class="mb-3">
            <strong>Date:</strong> ${formatDate(activity.date)}
        </div>
        ${activity.approved ? `
        <div class="mb-3">
            <strong>Approval:</strong> <span class="badge ${activity.approved === 'approved' ? 'bg-success' : 'bg-warning'}">${activity.approved}</span>
        </div>` : ''}
        ${activity.notes ? `
        <div class="mb-3">
            <strong>Notes:</strong><br>
            <div class="bg-light p-2 rounded">${activity.notes}</div>
        </div>` : ''}
    `;
    
    $('#activityDetailsContent').html(detailsContent);
    $('#activityDetailsModal').modal('show');
}

// Format date for display
function formatDate(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString(undefined, options);
}

// Update subtask dropdown based on selected task
function updateSubtasks() {
    const selectedTask = $('#taskName').val();
    const subtaskSelect = $('#subtaskName');
    
    subtaskSelect.empty().append('<option value="">Select a subtask</option>');
    
    if (selectedTask && taskSubtasks[selectedTask]) {
        taskSubtasks[selectedTask].forEach(subtask => {
            subtaskSelect.append(`<option value="${subtask}">${subtask}</option>`);
        });
    }
}

// Edit activity
function editSelectedActivity() {
    const activity = activities.find(act => act.id === selectedActivityId);
    if (!activity) return;
    
    // Populate the form with activity data
    $('#projectName').val(activity.projectName);
    $('#taskName').val(activity.taskName);
    updateSubtasks();
    $('#subtaskName').val(activity.subtaskName);
    $('#startTime').val(activity.startTime);
    $('#endTime').val(activity.endTime);
    $('#status').val(activity.status);
    $('#activityDate').val(activity.date);
    $('#notes').val(activity.notes);
    
    // Close the details modal and open the edit modal
    $('#activityDetailsModal').modal('hide');
    $('#addActivityModal').modal('show');

    initializeUserId()
}

// Delete activity
async function deleteSelectedActivity() {
    if (confirm('Are you sure you want to delete this activity?')) {
        try {
            await deleteActivityFromServer(selectedActivityId);
            
            // Remove from local array
            activities = activities.filter(act => act.id !== selectedActivityId);
            
            $('#activityDetailsModal').modal('hide');
            displayTodayActivities();
            
            if (calendar) {
                const event = calendar.getEventById(selectedActivityId);
                if (event) {
                    event.remove();
                }
            }
            
            showSuccessMessage('Activity deleted successfully');
        } catch (error) {
            showErrorMessage('Failed to delete activity: ' + (error.error || 'Unknown error'));
        }
    }
}

// Save a new activity or update existing one
async function saveActivity() {
    const projectName = $('#projectName').val();
    const taskName = $('#taskName').val();
    const subtaskName = $('#subtaskName').val();
    const startTime = $('#startTime').val();
    const endTime = $('#endTime').val();
    const status = $('#status').val();
    const activityDate = $('#activityDate').val();
    const notes = $('#notes').val();
    
    if (!projectName || !taskName || !startTime || !endTime || !status || !activityDate) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Validate time range
    if (startTime >= endTime) {
        alert('End time must be after start time');
        return;
    }
    
    const activityData = {
        projectName,
        taskName,
        subtaskName,
        startTime,
        endTime,
        status,
        date: activityDate,
        notes
    };
    
    if (selectedActivityId) {
        activityData.id = selectedActivityId;
    }
    
    try {
        const response = await saveActivityToServer(activityData);
        
        if (selectedActivityId) {
            // Update existing activity in local array
            const index = activities.findIndex(act => act.id === selectedActivityId);
            if (index !== -1) {
                activities[index] = response.task;
            }
        } else {
            // Add new activity to local array
            activities.push(response.task);
        }
        
        $('#addActivityModal').modal('hide');
        resetForm();
        displayTodayActivities();
        
        // Update calendar if it's initialized
        if (calendar) {
            calendar.removeAllEvents();
            calendar.addEventSource(getCalendarEvents());
        }
        
        showSuccessMessage('Activity saved successfully');
    } catch (error) {
        showErrorMessage('Failed to save activity: ' + (error.error || 'Unknown error'));
    }
}

// Reset the activity form
function resetForm() {
    $('#activityForm')[0].reset();
    selectedActivityId = null;
    
    // Set the date field to today
    const today = new Date().toISOString().split('T')[0];
    $('#activityDate').val(today);
    
    // Clear subtasks
    $('#subtaskName').empty().append('<option value="">Select a subtask</option>');
}

// Show the current date in the header
function showCurrentDate() {
    const today = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    $('#currentDate').text(today.toLocaleDateString(undefined, options));
}

// Generate a report based on selected filters
 function generateReport() {
            const dateRange = $('#dateRange').val();
            const groupBy = $('#groupBy').val();
            
            // Filter activities based on date range
            let filteredActivities = [];
            const today = new Date();
            
            switch (dateRange) {
                case 'week':
                    const oneWeekAgo = new Date();
                    oneWeekAgo.setDate(today.getDate() - 7);
                    filteredActivities = activities.filter(act => new Date(act.date) >= oneWeekAgo);
                    break;
                case 'month':
                    const oneMonthAgo = new Date();
                    oneMonthAgo.setMonth(today.getMonth() - 1);
                    filteredActivities = activities.filter(act => new Date(act.date) >= oneMonthAgo);
                    break;
                case 'quarter':
                    const threeMonthsAgo = new Date();
                    threeMonthsAgo.setMonth(today.getMonth() - 3);
                    filteredActivities = activities.filter(act => new Date(act.date) >= threeMonthsAgo);
                    break;
                default:
                    filteredActivities = [...activities];
                    break;
            }
            
            if (filteredActivities.length === 0) {
                $('#reportResults').html('<p class="text-center text-muted">No data available for the selected filters</p>');
                return;
            }
            
            // Create report based on grouping
            let reportContent = '';
            
            if (groupBy === 'location') {
                // Group by project
                const projectGroups = {};
                
                filteredActivities.forEach(act => {
                    if (!projectGroups[act.projectName]) {
                        projectGroups[act.projectName] = {
                            count: 0,
                            totalDuration: 0,
                            statuses: {}
                        };
                    }
                    
                    projectGroups[act.projectName].count++;
                    
                    // Calculate duration in minutes
                    const start = new Date(`2000-01-01T${act.startTime}:00`);
                    const end = new Date(`2000-01-01T${act.endTime}:00`);
                    const duration = (end - start) / (1000 * 60);
                    projectGroups[act.projectName].totalDuration += duration;
                    
                    // Count statuses
                    if (!projectGroups[act.projectName].statuses[act.status]) {
                        projectGroups[act.projectName].statuses[act.status] = 0;
                    }
                    projectGroups[act.projectName].statuses[act.status]++;
                });
                
                // Generate table
                reportContent = `
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="color: white;">
                                <tr>
                                    <th>Project</th>
                                    <th>Total Activities</th>
                                    <th>Total Duration</th>
                                    <th>Completed</th>
                                    <th>In Progress</th>
                                    <th>Completion %</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                for (const project in projectGroups) {
                    const group = projectGroups[project];
                    const totalHours = Math.floor(group.totalDuration / 60);
                    const totalMinutes = Math.floor(group.totalDuration % 60);
                    const durationText = totalHours > 0 ? `${totalHours}h ${totalMinutes}m` : `${totalMinutes}m`;
                    
                    const completed = group.statuses['done'] || 0;
                    const inProgress = (group.statuses['in-progress'] || 0) + (group.statuses['doing'] || 0) + (group.statuses['current'] || 0);
                    const completionPercentage = ((completed / group.count) * 100).toFixed(1);
                    
                    reportContent += `
                        <tr>
                            <td><strong>${project}</strong></td>
                            <td>${group.count}</td>
                            <td>${durationText}</td>
                            <td><span class="badge bg-success">${completed}</span></td>
                            <td><span class="badge bg-warning">${inProgress}</span></td>
                            <td>${completionPercentage}%</td>
                        </tr>
                    `;
                }
                
                reportContent += `
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <canvas id="projectChart" height="300"></canvas>
                    </div>
                `;
                
                $('#reportResults').html(reportContent);
                
                // Create chart
                setTimeout(() => {
                    const ctx = document.getElementById('projectChart').getContext('2d');
                    const projects = Object.keys(projectGroups);
                    const completedData = projects.map(proj => projectGroups[proj].statuses['done'] || 0);
                    const inProgressData = projects.map(proj => {
                        const group = projectGroups[proj];
                        return (group.statuses['in-progress'] || 0) + (group.statuses['doing'] || 0) + (group.statuses['current'] || 0);
                    });
                    const futureData = projects.map(proj => projectGroups[proj].statuses['future'] || 0);
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: projects,
                            datasets: [
                                {
                                    label: 'Completed',
                                    data: completedData,
                                    backgroundColor: '#28a745'
                                },
                                {
                                    label: 'In Progress',
                                    data: inProgressData,
                                    backgroundColor: '#ffc107'
                                },
                                {
                                    label: 'Future',
                                    data: futureData,
                                    backgroundColor: '#17a2b8'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Activities by Project'
                                }
                            },
                            scales: {
                                x: {
                                    stacked: true
                                },
                                y: {
                                    stacked: true,
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }, 100);
            } else {
                // Group by date (day, week, or month)
                const dateGroups = {};
                
                filteredActivities.forEach(act => {
                    let groupKey = act.date;
                    
                    if (groupBy === 'week') {
                        const actDate = new Date(act.date);
                        const weekStart = new Date(actDate);
                        weekStart.setDate(actDate.getDate() - actDate.getDay());
                        groupKey = weekStart.toISOString().split('T')[0];
                    } else if (groupBy === 'month') {
                        groupKey = act.date.substring(0, 7); // YYYY-MM
                    }
                    
                    if (!dateGroups[groupKey]) {
                        dateGroups[groupKey] = {
                            count: 0,
                            totalDuration: 0,
                            completed: 0
                        };
                    }
                    
                    dateGroups[groupKey].count++;
                    
                    // Calculate duration in minutes
                    const start = new Date(`2000-01-01T${act.startTime}:00`);
                    const end = new Date(`2000-01-01T${act.endTime}:00`);
                    const duration = (end - start) / (1000 * 60);
                    dateGroups[groupKey].totalDuration += duration;
                    
                    if (act.status === 'done') {
                        dateGroups[groupKey].completed++;
                    }
                });
                
                // Sort date keys
                const sortedKeys = Object.keys(dateGroups).sort();
                
                // Generate table
                reportContent = `
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="color: white;">
                                <tr>
                                    <th>${groupBy === 'day' ? 'Date' : groupBy === 'week' ? 'Week Starting' : 'Month'}</th>
                                    <th>Total Activities</th>
                                    <th>Total Duration</th>
                                    <th>Completed</th>
                                    <th>Completion %</th>
                                </tr>
                            </thead>
                            <tbody class="generate">
                `;
                
                sortedKeys.forEach(key => {
                    const group = dateGroups[key];
                    const totalHours = Math.floor(group.totalDuration / 60);
                    const totalMinutes = Math.floor(group.totalDuration % 60);
                    const durationText = totalHours > 0 ? `${totalHours}h ${totalMinutes}m` : `${totalMinutes}m`;
                    const completionPercentage = ((group.completed / group.count) * 100).toFixed(1);
                    
                    let displayDate = key;
                    if (groupBy === 'month') {
                        const date = new Date(key + '-01');
                        displayDate = date.toLocaleDateString(undefined, { year: 'numeric', month: 'long' });
                    } else {
                        displayDate = formatDate(key);
                    }
                    
                    reportContent += `
                        <tr>
                            <td>${displayDate}</td>
                            <td>${group.count}</td>
                            <td>${durationText}</td>
                            <td><span class="badge bg-success">${group.completed}</span></td>
                            <td>${completionPercentage}%</td>
                        </tr>
                    `;
                });
                
                reportContent += `
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <canvas id="timeChart" height="300"></canvas>
                    </div>
                `;
                
                $('#reportResults').html(reportContent);
                
                // Create chart
                setTimeout(() => {
                    const ctx = document.getElementById('timeChart').getContext('2d');
                    
                    const labels = sortedKeys.map(key => {
                        if (groupBy === 'month') {
                            const date = new Date(key + '-01');
                            return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short' });
                        } else {
                            return formatDate(key);
                        }
                    });
                    
                    const activityData = sortedKeys.map(key => dateGroups[key].count);
                    const completedData = sortedKeys.map(key => dateGroups[key].completed);
                    const durationData = sortedKeys.map(key => Math.round(dateGroups[key].totalDuration / 60 * 10) / 10); // Convert to hours
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Total Activities',
                                    data: activityData,
                                    borderColor: '#1a73e8',
                                    backgroundColor: 'rgba(26, 115, 232, 0.1)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.1,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Completed',
                                    data: completedData,
                                    borderColor: '#28a745',
                                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.1,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Duration (Hours)',
                                    data: durationData,
                                    borderColor: '#fd7e14',
                                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.1,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: `Activities by ${groupBy.charAt(0).toUpperCase() + groupBy.slice(1)}`
                                }
                            },
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: groupBy.charAt(0).toUpperCase() + groupBy.slice(1)
                                    }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Number of Activities'
                                    },
                                    beginAtZero: true
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Duration (Hours)'
                                    },
                                    beginAtZero: true,
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            }
                        }
                    });
                }, 100);
            }
        }

// Document ready
$(document).ready(async function() {
    // Initialize user ID
    initializeUserId();
    
    // Show current date
    showCurrentDate();
    
    // Set today's date as default in the form
    const today = new Date().toISOString().split('T')[0];
    $('#activityDate').val(today);
    
    // Load data from server
    try {
        await loadSubtasks();
        await loadActivities();
        
        // Display today's activities
        displayTodayActivities();
    } catch (error) {
        console.error('Error initializing app:', error);
        showErrorMessage('Failed to initialize application. Please refresh the page.');
    }
    
    // Task change event to update subtasks
    $('#taskName').change(updateSubtasks);
    
    // Tab navigation
    $('#todayLink').click(function(e) {
        e.preventDefault();
        $(this).addClass('active').siblings().removeClass('active');
        $('#today').addClass('show active').siblings().removeClass('show active');
    });
    
    $('#calendarLink').click(function(e) {
        e.preventDefault();
        $(this).addClass('active').siblings().removeClass('active');
        $('#calendar-view').addClass('show active').siblings().removeClass('show active');
        
        // Initialize calendar if not already done
        if (!calendar) {
            initCalendar();
        } else {
            calendar.render();
        }
    });
    
    $('#reportsLink').click(function(e) {
        e.preventDefault();
        $(this).addClass('active').siblings().removeClass('active');
        $('#reports').addClass('show active').siblings().removeClass('show active');
    });
    
    // Save activity button click
    $('#saveActivity').click(saveActivity);
    
    // Edit activity button click
    $('#editActivity').click(editSelectedActivity);
    
    // Delete activity button click
    $('#deleteActivity').click(deleteSelectedActivity);
    
    // Generate report button click
    $('#generateReport').click(generateReport);
    
    // Reset the form when the modal is closed
    $('#addActivityModal').on('hidden.bs.modal', resetForm);
});