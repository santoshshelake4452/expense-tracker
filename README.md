# 💸 Smart Expense Tracker

A clean, lightweight expense tracker built with PHP, MySQL, and vanilla JavaScript. Supports voice input, category filtering, budget limits, and works both offline (LocalStorage) and with a XAMPP/MySQL backend.

---

## Features

- **Add / Edit / Delete** expenses with description, amount, category, and date
- **Budget limit** — blocks new expenses when monthly budget is exceeded
- **Voice input** — speak your expense and fields auto-fill (Chrome only)
- **Search & filter** by description, amount, or category
- **Two modes** — Local Storage (offline) or XAMPP MySQL (persistent DB)
- **Live stats** — total spent, entry count, highest expense
- **Budget progress bar** — color-coded warnings at 75%, 90%, and 100%

---

## Tech Stack

| Layer    | Technology             |
|----------|------------------------|
| Frontend | HTML, Tailwind CSS, Vanilla JS |
| Backend  | PHP 8+                 |
| Database | MySQL (via XAMPP)      |
| Icons    | Lucide Icons           |
| Fonts    | Inter, Space Grotesk   |

---

## Getting Started

### Requirements
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)
- A modern browser (Chrome recommended for voice input)

### Setup

**1. Place files in htdocs**
```
C:/xampp/htdocs/expense-tracker/
    ├── index.php
    └── api.php
```

**2. Start XAMPP**

Open XAMPP Control Panel and start both **Apache** and **MySQL**.

**3. Open in browser**
```
http://localhost/expense_tracker/index.php
```

The database and tables are created automatically on first load. No manual SQL setup needed.

---

## How It Works

### Data Modes

| Mode | When | Storage |
|------|------|---------|
| **Local Mode** | Opened as `file://` | Browser LocalStorage |
| **XAMPP PHP API** | Served via `http://localhost` | MySQL Database |

Click the mode badge in the top-right to toggle between modes.

### API Endpoints (`api.php`)

| Method | Action | Description |
|--------|--------|-------------|
| GET | `?action=profile` | Fetch user profile & budget |
| POST/PUT | `?action=profile` | Update profile & budget |
| GET | `?action=expenses` | Fetch all expenses |
| POST | `?action=expenses` | Add new expense |
| PUT | `?action=expenses` | Update existing expense |
| DELETE | `?action=expenses&id=X` | Delete expense by ID |

---

## Budget Restriction

When a monthly budget is set in **Settings**:

- A progress bar shows spending vs. budget
- **75%** used → bar turns amber
- **90%** used → red warning appears
- **100%** reached → new expenses are **blocked**

To add more expenses after hitting the limit, increase the budget via the **Settings** button.

---

## Voice Input

Click the 🎤 microphone button in the Add Expense modal and speak naturally:

```
"Lunch 250 food"
"Auto fare 80 transport"
"Medicine 150 health"
```

The app will auto-fill description, amount, and category. Works in **Google Chrome** only (Web Speech API).

---

## Troubleshooting

**"Unable to connect to XAMPP PHP Backend"**
- Ensure Apache and MySQL are both running in XAMPP Control Panel
- Confirm both `index.php` and `api.php` are in the same folder under `htdocs`
- Test the API directly: `http://localhost/expense-tracker/api.php?action=profile`
- If the path is wrong, hardcode it in `index.php`:
  ```javascript
  const PHP_API_BASE_URL = "/expense_tracker/api.php";
  ```

**Voice input not working**
- Use Google Chrome
- Allow microphone permissions when prompted

---

## Folder Structure

```
expense-tracker/
├── index.php      # Frontend UI + JavaScript logic
└── api.php        # PHP REST API + DB connection
    db.sql         # Database file

---

## License

Free to use and modify for personal projects.
