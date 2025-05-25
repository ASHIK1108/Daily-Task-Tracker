# 🗓️ Daily Task Tracker

A web-based dashboard to log, view, and analyze daily tasks by users. Includes task summaries, productivity stats, and real-time filtering by date and status.

## 🚀 Features

- Add, view, and track daily tasks
- Subtask and note support
- Productivity summary (total tasks, completed tasks, hours today)
- User dropdown with logout
- JSON-based backend data storage
- Responsive card layout dashboard

## 🧱 Technologies Used

- HTML, CSS (Bootstrap)
- JavaScript (jQuery)
- PHP (for backend logic)
- JSON (for task storage)
- FontAwesome (for icons)

## 📂 Folder Structure

/
├── index.php # Dashboard main file
├── modules/
│ ├── get_data.php # Returns task data as JSON
│ └── logout # Handles logout logic
├── assets/
│ ├── css/
│ ├── js/
│ └── icons/
├── data/
│ └── tasks.json # Stores user task entries
└── README.md





##  📦 Setup Instructions
- Clone the repository or download the source.

- Make sure your server supports PHP (e.g., XAMPP, MAMP).

- Place the files in the htdocs or public folder.

- Start your PHP server and navigate to http://localhost/your-folder/.


## ✅ Usage
- Users can log in and see their task stats.

- Tasks can be added or loaded dynamically.

- Stats update automatically: total tasks, completed count, hours spent today, and productivity.

## ⚙️ Functions Overview
- loadActivities(): Fetches tasks and updates stats

- parseTime(): Converts string time (HH:mm) to JS Date object

-User dropdown: Shows user info and logout option


