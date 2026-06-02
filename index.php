<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Expense Tracker & Budget Advisor</title>
  
  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Google Fonts pairing for pristine visual design -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    /* Styling overrides with Tailwind configurations */
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, .font-heading {
      font-family: 'Space Grotesk', sans-serif;
    }
    .font-mono {
      font-family: 'JetBrains Mono', monospace;
    }
    /* Hide scrollbars but keep standard functional layout */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type="number"] {
      -moz-appearance: textfield;
    }
    
    /* Elegant soft pulse animations for listening button */
    @keyframes pulse-ring {
      0% { transform: scale(0.95); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
      100% { transform: scale(0.95); opacity: 0.5; }
    }
    .listening-pulse {
      animation: pulse-ring 1.8s infinite ease-in-out;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen antialiased py-8 px-4 flex items-center justify-center">

  <main class="w-full max-w-2xl bg-white border border-slate-100 rounded-[2.5rem] p-6 md:p-8 shadow-sm space-y-6 relative overflow-hidden">
    
    <!-- Header Block: Config Panel Trigger -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
      <div class="flex items-center gap-3.5">
        <div id="p-avatar" class="w-12 h-12 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm shadow-xs select-none">
          SS
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h2 id="p-name" class="text-sm font-bold text-slate-900">Santya Shelake</h2>
            <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100/35 px-1.5 py-0.5 rounded-full uppercase tracking-wider">
              Profile Active
            </span>
          </div>
          <p id="p-email" class="text-xs text-slate-500 font-medium tracking-wide mt-0.5">
            santyashelake@gmail.com
          </p>
        </div>
      </div>
      
      <div class="flex items-center gap-2 justify-end">
        <!-- Edit Profile Trigger -->
        <button onclick="openProfileModal()" class="flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-xs font-semibold text-slate-700 rounded-xl transition-all duration-150 cursor-pointer shadow-xs">
          <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i>
          <span>Settings</span>
        </button>
        <!-- XAMPP Connection Indicator Toggle -->
        <button id="toggle-source-btn" onclick="toggleDataSource()" class="flex items-center gap-1.5 px-3 py-2 bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-800 rounded-xl transition-all duration-150 cursor-pointer shadow-xs">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span id="source-label">Local Mode</span>
        </button>
      </div>
    </div>

    <!-- Main Section Header block -->
    <header class="flex items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <span class="p-1.5 bg-indigo-50 border border-indigo-100/50 rounded-xl text-indigo-600">
            <i data-lucide="credit-card" class="w-5 h-5"></i>
          </span>
          <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
            Expenses
          </h1>
        </div>
        <!-- Current calendar date label context -->
        <p id="month-label" class="text-xs text-slate-550 font-bold uppercase tracking-wider mt-1.5">
          June 2026
        </p>
      </div>

      <!-- Call out action elements -->
      <button onclick="openExpenseModal()" class="h-11 px-5 rounded-2xl text-xs font-bold bg-slate-800 hover:bg-slate-900 hover:shadow-md text-white transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Add expense</span>
      </button>
    </header>

    <!-- Monthly Budget exhaustion indicator gauge -->
    <div id="budget-progress-block" class="p-4 bg-indigo-50/40 border border-indigo-100/30 rounded-2xl hidden">
      <div class="flex justify-between items-center mb-2">
        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">
          Monthly Budget Limit
        </span>
        <span id="budget-text" class="text-xs font-bold text-indigo-800">
          ₹0 / ₹0 (0%)
        </span>
      </div>
      <div class="w-full bg-slate-200/60 rounded-full h-2.5 overflow-hidden">
        <div id="budget-fill" class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: 0%"></div>
      </div>
      <!-- Budget remaining info line -->
      <p id="budget-remaining-text" class="text-[10px] text-slate-500 font-semibold mt-1.5 hidden"></p>
      <!-- Over 90% warning -->
      <p id="budget-alert-warning" class="text-[10px] text-rose-600 font-bold uppercase tracking-wider mt-2.5 hidden flex items-center gap-1 animate-pulse">
        <span>⚠️ Warning: You've consumed over 90% of your budget limit!</span>
      </p>
      <!-- Budget fully exceeded banner -->
      <p id="budget-exceeded-banner" class="text-[10px] text-rose-700 font-bold uppercase tracking-wider mt-2.5 hidden flex items-center gap-1">
        <span>🚫 Budget limit reached! New expenses are blocked until budget is increased.</span>
      </p>
    </div>

    <!-- Numeric Stats Highlights (Total Spent, Entries total count, Max expense item) -->
    <div class="grid grid-cols-3 gap-3 md:gap-4 font-heading">
      <!-- Total cost card -->
      <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 transition-all duration-200">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Total Spent</span>
          <i data-lucide="landmark" class="w-4 h-4 text-rose-500"></i>
        </div>
        <div id="stat-total" class="text-lg md:text-2xl font-bold tracking-tight text-rose-600">
          ₹0
        </div>
      </div>

      <!-- Transaction count card -->
      <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 transition-all duration-200">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Entries</span>
          <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
        </div>
        <div id="stat-count" class="text-lg md:text-2xl font-bold tracking-tight text-slate-900">
          0
        </div>
      </div>

      <!-- Max transaction size card -->
      <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 hover:border-slate-200 transition-all duration-200">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Highest</span>
          <i data-lucide="trending-up" class="w-4 h-4 text-emerald-500"></i>
        </div>
        <div id="stat-max" class="text-lg md:text-2xl font-bold tracking-tight text-slate-905">
          ₹0
        </div>
      </div>
    </div>

    <!-- Filters, search indices and query toolbar controllers -->
    <div class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400 pointer-events-none"></i>
        <input
          type="text"
          id="search-input"
          oninput="handleSearchFilterChange()"
          placeholder="Search by description, amount, category..."
          class="w-full pl-10 pr-4 h-11 bg-slate-50 border border-slate-200 focus:border-slate-400 focus:bg-white rounded-2xl text-sm placeholder-slate-400 outline-none transition-colors"
        >
      </div>

      <div class="relative">
        <i data-lucide="sliders-horizontal" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"></i>
        <select
          id="cat-filter"
          onchange="handleSearchFilterChange()"
          class="pl-9 pr-8 h-11 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 tracking-wide outline-none appearance-none cursor-pointer focus:border-slate-400 transition-colors"
        >
          <option value="">All Categories</option>
          <option value="Food">🍽️ Food</option>
          <option value="Transport">🚗 Transport</option>
          <option value="Shopping">🛍️ Shopping</option>
          <option value="Health">🏥 Health</option>
          <option value="Entertainment">🎬 Entertainment</option>
          <option value="Other">📦 Other</option>
        </select>
      </div>
    </div>

    <!-- Expenses Listing wrapper -->
    <section id="expenses-list" class="space-y-2.5 min-h-[150px]">
      <!-- Loaded dynamically via javascript template tags -->
    </section>

    <!-- Empty feedback state container block -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 border border-dashed border-slate-250 rounded-3xl bg-slate-50">
      <span class="p-3 bg-slate-100 rounded-full text-slate-400 inline-block mb-3">
        <i data-lucide="filter" class="w-6 h-6 stroke-[1.5]"></i>
      </span>
      <h3 class="text-sm font-semibold text-slate-700">No expenses found</h3>
      <p class="text-xs text-slate-450 font-medium text-center max-w-xs mt-1">
        Try modifying your search filter, checking another category, or speaking an input.
      </p>
    </div>

    <!-- Voice parsing / custom details overlay hint footer info -->
    <footer class="mt-8 text-center border-t border-slate-50 pt-4">
      <p class="text-[10px] text-slate-400 font-bold tracking-wider uppercase flex items-center justify-center gap-1.5 select-none">
        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-400 animate-pulse"></i>
        <span>Adaptive Intelligent Speech-to-Text Enabled</span>
      </p>
    </footer>

  </main>


  <!-- EXPENSE DIALOG MODAL LAYOUT -->
  <div id="expense-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4 z-50 overflow-y-auto">
    <!-- Click backdrop to exit modal trigger -->
    <div class="absolute inset-0" onclick="closeExpenseModal()"></div>
    
    <div class="relative bg-white border border-slate-100 rounded-[2rem] p-6 w-full max-w-md shadow-2xl z-10 space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h2 id="modal-title" class="text-lg font-extrabold text-slate-800">Add Expense</h2>
        <button onclick="closeExpenseModal()" class="p-1 rounded-lg text-slate-450 hover:text-slate-600 hover:bg-slate-50 transition-colors">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Alert Panel for validation warnings -->
      <div id="val-error-alert" class="hidden flex items-center gap-2 p-2.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
        <span id="val-error-text">Please provide valid inputs.</span>
      </div>

      <!-- Single form parameters -->
      <div class="space-y-4">
        <!-- Item description label -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Description</label>
          <div class="flex gap-2">
            <input
              type="text"
              id="f-desc"
              placeholder="e.g. Lunch at restaurant café"
              class="flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-slate-400 focus:bg-white rounded-xl text-sm placeholder-slate-400 outline-none transition-colors"
            >
            <!-- Microphone trigger -->
            <button
              id="voice-mic-btn"
              onclick="startVoiceCapture()"
              class="w-11 h-11 bg-slate-50 border border-slate-200 text-slate-600 rounded-xl flex items-center justify-center transition-all duration-200"
              title="Speak to parse expense data"
            >
              <i data-lucide="mic" class="w-5 h-5"></i>
            </button>
          </div>
          <p id="voice-helper-hint" class="text-xs text-slate-400 mt-1.5 font-medium leading-relaxed">
            Tap 🎤 and speak. Format: "Lunch 250 food" or "Auto fare 80 transport".
          </p>
        </div>

        <!-- Rupee amount input -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Amount (₹)</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">₹</span>
            <input
              type="number"
              id="f-amount"
              placeholder="0.00"
              step="any"
              class="w-full pl-8 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-slate-400 focus:bg-white rounded-xl text-sm placeholder-slate-450 outline-none transition-colors"
            >
          </div>
        </div>

        <!-- Category badge selectors -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category</label>
          <div class="grid grid-cols-3 gap-2" id="modal-category-badges">
            <button onclick="setCategorySelection('Food')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Food">
              <span>🍽️</span> <span>Food</span>
            </button>
            <button onclick="setCategorySelection('Transport')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Transport">
              <span>🚗</span> <span>Transport</span>
            </button>
            <button onclick="setCategorySelection('Shopping')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Shopping">
              <span>🛍️</span> <span>Shopping</span>
            </button>
            <button onclick="setCategorySelection('Health')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Health">
              <span>🏥</span> <span>Health</span>
            </button>
            <button onclick="setCategorySelection('Entertainment')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Entertainment">
              <span>🎬</span> <span>Entertainment</span>
            </button>
            <button onclick="setCategorySelection('Other')" class="cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100" data-val="Other">
              <span>📦</span> <span>Other</span>
            </button>
          </div>
        </div>

        <!-- Date select block -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
          <input
            type="date"
            id="f-date"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-205 focus:border-slate-400 focus:bg-white rounded-xl text-sm outline-none transition-colors"
          >
        </div>

        <!-- Confirmations & submittals row buttons -->
        <div class="flex gap-3 pt-4 border-t border-slate-100">
          <button onclick="closeExpenseModal()" class="flex-1 h-11 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
            Cancel
          </button>
          <button onclick="handleExpenseFormSave()" class="flex-1 h-11 bg-slate-800 text-white hover:bg-slate-900 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-1.5">
            <i data-lucide="check" class="w-4 h-4"></i>
            <span id="save-btn-text">Save</span>
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- PROFILE EDITING SETTINGS DIALOG MODAL -->
  <div id="profile-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4 z-50 overflow-y-auto">
    <!-- Click backdrop to exit modal trigger -->
    <div class="absolute inset-0" onclick="closeProfileModal()"></div>
    
    <div class="relative bg-white border border-slate-100 rounded-[2rem] p-6 w-full max-w-md shadow-2xl z-10 space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-1.5">
          <i data-lucide="user" class="w-5 h-5 text-indigo-500"></i>
          <span>Edit Profile Settings</span>
        </h2>
        <button onclick="closeProfileModal()" class="p-1 rounded-lg text-slate-450 hover:text-slate-600 hover:bg-slate-50 transition-colors">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Alert Panel for validation warnings -->
      <div id="profile-error-alert" class="hidden flex items-center gap-2 p-2.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
        <span id="profile-error-text">Please verify profile fields.</span>
      </div>

      <!-- Single profile form parameters -->
      <div class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
          <div class="relative">
            <i data-lucide="user" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
            <input
              type="text"
              id="fp-name"
              placeholder="e.g. Santya Shelake"
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-slate-400 focus:bg-white rounded-xl text-sm outline-none transition-colors"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
          <div class="relative">
            <i data-lucide="mail" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
            <input
              type="email"
              id="fp-email"
              placeholder="e.g. email@example.com"
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-slate-400 focus:bg-white rounded-xl text-sm outline-none transition-colors"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Monthly Budget (₹)</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">₹</span>
            <input
              type="number"
              id="fp-budget"
              placeholder="e.g. 15000"
              class="w-full pl-8 pr-4 py-2.5 bg-slate-50 border border-slate-205 focus:border-slate-400 focus:bg-white rounded-xl text-sm outline-none transition-colors"
            >
          </div>
        </div>

        <!-- Confirmations & submittals row buttons -->
        <div class="flex gap-3 pt-4 border-t border-slate-100">
          <button onclick="closeProfileModal()" class="flex-1 h-11 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
            Cancel
          </button>
          <button onclick="handleProfileFormSave()" class="flex-1 h-11 bg-slate-800 text-white hover:bg-slate-900 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-1.5">
            <i data-lucide="check" class="w-4 h-4"></i>
            <span>Save Updates</span>
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- CORE FUNCTIONAL OPERATIONS LOGIC -->
  <script>
    // ------------------------------------------------------------------
    // 1. DATA SOURCES & CONFIGURATION SCRIPT
    // ------------------------------------------------------------------
    // Dynamically auto-detect current directory for api.php to prevent hardcoded folder mismatch bugs.
const PHP_API_BASE_URL = "/expense_tracker/api.php";    // Status flag tracking current source: LOCAL (true) or XAMPP PHP API (false)
    // If opened as local file (file://), default to Local Mode (localStorage).
    // If served via Apache/webserver (http:// or https://), automatically default to active XAMPP API Mode.
    let isLocalDataSource = (window.location.protocol === 'file:');

    // Default starter core elements
    const DEFAULT_EXPENSES = [
      { id: 1, desc: 'Grocery run at supermarket', amount: 842, cat: 'Food', date: '2026-06-01' },
      { id: 2, desc: 'Auto fare to office office', amount: 65, cat: 'Transport', date: '2026-06-02' },
      { id: 3, desc: 'New high-fidelity headphones', amount: 1499, cat: 'Shopping', date: '2026-06-02' },
      { id: 4, desc: 'Movie tickets for weekend show', amount: 340, cat: 'Entertainment', date: '2026-06-03' },
      { id: 5, desc: 'Prescription medicines', amount: 210, cat: 'Health', date: '2026-06-04' }
    ];

    const CAT_ICONS = {
      Food: '🍽️',
      Transport: '🚗',
      Shopping: '🛍️',
      Health: '🏥',
      Entertainment: '🎬',
      Other: '📦'
    };

    const CAT_BG = {
      Food: '#FFF3EA',
      Transport: '#E8F4FF',
      Shopping: '#F5EAFF',
      Health: '#EAFFF3',
      Entertainment: '#FFF8E1',
      Other: '#F3F3F3'
    };

    // Global lists mapping state
    let expenses = [];
    let profile = {
      name: 'Santya Shelake',
      email: 'santyashelake@gmail.com',
      budget: 15000
    };

    let expenseEditId = null;
    let selectedFormCategory = 'Food';
    let recognitionInstance = null;

    // ------------------------------------------------------------------
    // 2. NETWORK OPERATIONS INTEGRATIONS (XAMPP PHP API / LOCALSTORAGE)
    // ------------------------------------------------------------------
    
    // Switch between LocalStorage and XAMPP DB backend
    function toggleDataSource() {
      isLocalDataSource = !isLocalDataSource;
      loadAllData();
    }

    // Try reading from XAMPP, fall back to local if it's missing / disconnected
    async function testAndLoadXamppData() {
      try {
        const response = await fetch(`${PHP_API_BASE_URL}?action=profile`, { method: 'GET' });
        if (!response.ok) throw new Error("API unhealthy");
        
        // Successfully connected to php backend!
        await fetchPhpProfile();
        await fetchPhpExpenses();
      } catch (err) {
        console.error("XAMPP PHP Backend offline or folder mismatch. Falling back to Local Mode.", err);
        alert("Unable to connect to XAMPP PHP Backend at " + PHP_API_BASE_URL + "\n\n1. Ensure Apache and MySQL are running in your XAMPP Control Panel.\n2. Ensure both 'api.php' and this file are placed inside the same folder under 'xampp/htdocs/'.\n\nFalling back to Local Storage mode.");
        isLocalDataSource = true;
        loadAllData();
      }
    }

    // Load elements
    function loadAllData() {
      if (isLocalDataSource) {
        // Read LocalStorage
        try {
          const storedProfile = localStorage.getItem('exp_profile');
          if (storedProfile) {
            profile = JSON.parse(storedProfile);
          }
          
          const storedExpenses = localStorage.getItem('exp_v2');
          if (storedExpenses) {
            expenses = JSON.parse(storedExpenses);
          } else {
            expenses = [...DEFAULT_EXPENSES];
          }
        } catch (e) {
          console.error(e);
          expenses = [...DEFAULT_EXPENSES];
        }
        renderUI();
      } else {
        testAndLoadXamppData();
      }
    }

    // Write elements
    function saveLocalData() {
      if (isLocalDataSource) {
        try {
          localStorage.setItem('exp_profile', JSON.stringify(profile));
          localStorage.setItem('exp_v2', JSON.stringify(expenses));
        } catch (e) {
          console.error(e);
        }
      }
    }

    // Fetch Profile from PHP API
    async function fetchPhpProfile() {
      const res = await fetch(`${PHP_API_BASE_URL}?action=profile`);
      const data = await res.json();
      if (data && !data.error) {
        profile = {
          name: data.name,
          email: data.email,
          budget: parseFloat(data.budget)
        };
        renderUI();
      }
    }

    // Fetch Expenses list from PHP API
    async function fetchPhpExpenses() {
      const res = await fetch(`${PHP_API_BASE_URL}?action=expenses`);
      const data = await res.json();
      if (Array.isArray(data)) {
        expenses = data;
        renderUI();
      }
    }

    // Save/Update Profile to PHP API
    async function savePhpProfile(updated) {
      const res = await fetch(`${PHP_API_BASE_URL}?action=profile`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updated)
      });
      const resData = await res.json();
      return resData;
    }

    // Save/Update/Delete Expenses to PHP API
    async function insertPhpExpense(expense) {
      const res = await fetch(`${PHP_API_BASE_URL}?action=expenses`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(expense)
      });
      return await res.json();
    }

    async function updatePhpExpense(expense) {
      const res = await fetch(`${PHP_API_BASE_URL}?action=expenses`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(expense)
      });
      return await res.json();
    }

    async function deletePhpExpense(id) {
      const res = await fetch(`${PHP_API_BASE_URL}?action=expenses&id=${id}`, {
        method: 'DELETE'
      });
      return await res.json();
    }


    // ------------------------------------------------------------------
    // 3. UI RENDER ENGINE
    // ------------------------------------------------------------------
    function fmtRupee(n) {
      return '₹' + Number(n).toLocaleString('en-IN', {
        maximumFractionDigits: 2,
        minimumFractionDigits: 0
      });
    }

    function formatDate(dateStr) {
      try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          year: 'numeric'
        });
      } catch {
        return dateStr;
      }
    }

    function renderUI() {
      // Update dynamic source indicator badge
      const btn = document.getElementById('toggle-source-btn');
      const label = document.getElementById('source-label');
      if (isLocalDataSource) {
        btn.className = "flex items-center gap-1.5 px-3 py-2 bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 rounded-xl transition-all duration-150 cursor-pointer shadow-xs active:scale-95";
        label.innerHTML = "Local Mode";
      } else {
        btn.className = "flex items-center gap-1.5 px-3 py-2 bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-800 rounded-xl transition-all duration-150 cursor-pointer shadow-xs active:scale-95";
        label.innerHTML = "XAMPP PHP API";
      }

      // 1. Render User profile info
      document.getElementById('p-name').textContent = profile.name;
      document.getElementById('p-email').textContent = profile.email;
      
      const initials = profile.name
        .split(' ')
        .map(part => part[0])
        .join('')
        .toUpperCase()
        .substring(0, 2) || 'PP';
      document.getElementById('p-avatar').textContent = initials;

      // 2. Compute numeric sums and statistics
      const total = expenses.reduce((sum, item) => sum + Number(item.amount), 0);
      const count = expenses.length;
      const max = count ? Math.max(...expenses.map(item => Number(item.amount))) : 0;

      document.getElementById('stat-total').textContent = fmtRupee(total);
      document.getElementById('stat-count').textContent = count;
      document.getElementById('stat-max').textContent = fmtRupee(max);

      // 3. Render Monthly budget health metrics progress indicator
      const progressBlock = document.getElementById('budget-progress-block');
      if (profile.budget > 0) {
        progressBlock.classList.remove('hidden');
        const percentage = Math.min(Math.round((total / profile.budget) * 100), 100);
        const remaining = Math.max(profile.budget - total, 0);

        document.getElementById('budget-text').textContent =
          `${fmtRupee(total)} / ${fmtRupee(profile.budget)} (${percentage}%)`;

        // Show remaining budget line
        const remainingEl = document.getElementById('budget-remaining-text');
        remainingEl.classList.remove('hidden');
        if (total >= profile.budget) {
          remainingEl.textContent = '🚫 No budget remaining.';
          remainingEl.className = "text-[10px] text-rose-600 font-bold mt-1.5";
        } else {
          remainingEl.textContent = `Remaining: ${fmtRupee(remaining)}`;
          remainingEl.className = "text-[10px] text-slate-500 font-semibold mt-1.5";
        }
        
        const fillBar = document.getElementById('budget-fill');
        fillBar.style.width = `${percentage}%`;
        
        // Color themes matching exhaustion rates
        if (percentage >= 100) {
          fillBar.className = "h-full bg-rose-600 rounded-full transition-all duration-500";
          document.getElementById('budget-alert-warning').classList.add('hidden');
          document.getElementById('budget-exceeded-banner').classList.remove('hidden');
          document.getElementById('budget-exceeded-banner').classList.add('flex');
        } else if (percentage >= 90) {
          fillBar.className = "h-full bg-rose-500 rounded-full transition-all duration-500";
          document.getElementById('budget-alert-warning').classList.remove('hidden');
          document.getElementById('budget-exceeded-banner').classList.add('hidden');
        } else if (percentage >= 75) {
          fillBar.className = "h-full bg-amber-500 rounded-full transition-all duration-500";
          document.getElementById('budget-alert-warning').classList.add('hidden');
          document.getElementById('budget-exceeded-banner').classList.add('hidden');
        } else {
          fillBar.className = "h-full bg-indigo-600 rounded-full transition-all duration-500";
          document.getElementById('budget-alert-warning').classList.add('hidden');
          document.getElementById('budget-exceeded-banner').classList.add('hidden');
        }
      } else {
        progressBlock.classList.add('hidden');
      }

      // 4. Render Expenses List (including searches and filters)
      renderExpensesList();
    }

    function renderExpensesList() {
      const listContainer = document.getElementById('expenses-list');
      const emptyState = document.getElementById('empty-state');
      
      const q = document.getElementById('search-input').value.toLowerCase().trim();
      const categoryFilter = document.getElementById('cat-filter').value;

      // Clean filter evaluations
      const list = expenses.filter(e => {
        const matchQ = !q || e.desc.toLowerCase().includes(q) || e.cat.toLowerCase().includes(q) || String(e.amount).includes(q);
        const matchC = !categoryFilter || e.cat === categoryFilter;
        return matchQ && matchC;
      }).sort((a, b) => new Date(b.date) - new Date(a.date));

      if (list.length === 0) {
        listContainer.innerHTML = '';
        emptyState.classList.remove('hidden');
        emptyState.classList.add('flex');
        return;
      }

      emptyState.classList.add('hidden');
      emptyState.classList.remove('flex');

      listContainer.innerHTML = list.map(item => {
        const icon = CAT_ICONS[item.cat] || '📦';
        const bg = CAT_BG[item.cat] || '#f3f3f3';
        
        return `
          <div class="group flex items-center justify-between gap-4 p-3.5 bg-white border border-slate-100 rounded-2xl hover:border-slate-300 hover:shadow-xs transition-all duration-200">
            <div class="flex items-center gap-3.5 min-w-0">
              <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl shadow-xs" style="background-color: ${bg}">
                ${icon}
              </div>
              <div class="min-w-0">
                <h4 class="text-sm font-semibold text-slate-800 truncate leading-snug group-hover:text-slate-950 transition-colors">
                  ${escapeHtml(item.desc)}
                </h4>
                <p class="text-xs text-slate-500 mt-1 tracking-wide font-medium">
                  ${item.cat} <span class="text-slate-300 mx-1">•</span> ${formatDate(item.date)}
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
              <span class="text-sm md:text-base font-bold text-rose-600 tabular-nums">
                ${fmtRupee(item.amount)}
              </span>

              <!-- Action editing buttons -->
              <div class="flex items-center gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                <button onclick="editExpenseEntry(${item.id})" class="p-1.5 hover:bg-slate-50 active:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Edit expense">
                  <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </button>
                <button onclick="deleteExpenseEntry(${item.id})" class="p-1.5 hover:bg-rose-50 active:bg-rose-100 rounded-lg text-slate-400 hover:text-rose-600 transition-colors cursor-pointer" title="Delete expense">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </div>
            </div>
          </div>
        `;
      }).join('');

      // Render icons inside injected HTML tags
      lucide.createIcons();
    }

    function handleSearchFilterChange() {
      renderExpensesList();
    }

    function escapeHtml(str) {
      return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }


    // ------------------------------------------------------------------
    // 4. EXPENSE EDIT & CREATION LOGIC Modals
    // ------------------------------------------------------------------
    function openExpenseModal(id = null) {
      expenseEditId = id;
      const modal = document.getElementById('expense-modal');
      const errPanel = document.getElementById('val-error-alert');
      errPanel.classList.add('hidden');

      document.getElementById('voice-helper-hint').innerHTML = 'Tap 🎤 and speak. Format: "Lunch 250 food" or "Auto fare 80 transport".';
      
      if (id) {
        // Edit record
        const item = expenses.find(x => x.id === id);
        document.getElementById('modal-title').textContent = "Edit Expense";
        document.getElementById('f-desc').value = item.desc;
        document.getElementById('f-amount').value = item.amount;
        document.getElementById('f-date').value = item.date;
        setCategorySelection(item.cat);
        document.getElementById('save-btn-text').textContent = "Update";
      } else {
        // New record — check if budget is already exceeded before opening
        const currentTotal = expenses.reduce((sum, e) => sum + Number(e.amount), 0);
        if (profile.budget > 0 && currentTotal >= profile.budget) {
          alert(`🚫 Budget Exceeded!\n\nYou have already reached your monthly budget of ${fmtRupee(profile.budget)}.\n\nPlease increase your budget in Settings to add more expenses.`);
          return;
        }

        document.getElementById('modal-title').textContent = "Add Expense";
        document.getElementById('f-desc').value = "";
        document.getElementById('f-amount').value = "";
        document.getElementById('f-date').value = new Date().toISOString().split('T')[0];
        setCategorySelection('Food');
        document.getElementById('save-btn-text').textContent = "Save";
      }

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      lucide.createIcons();
    }

    function closeExpenseModal() {
      document.getElementById('expense-modal').classList.remove('flex');
      document.getElementById('expense-modal').classList.add('hidden');
      stopVoiceCapture();
    }

    function setCategorySelection(cat) {
      selectedFormCategory = cat;
      const elements = document.querySelectorAll('.cat-badge-el');
      elements.forEach(btn => {
        const val = btn.getAttribute('data-val');
        if (val === cat) {
          btn.className = "cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-900 bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-xs cursor-pointer";
        } else {
          btn.className = "cat-badge-el flex items-center justify-center gap-1.5 py-2 px-1 border border-slate-200 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100 cursor-pointer";
        }
      });
    }

    async function handleExpenseFormSave() {
      const desc = document.getElementById('f-desc').value.trim();
      const amountVal = parseFloat(document.getElementById('f-amount').value);
      const date = document.getElementById('f-date').value;
      const errPanel = document.getElementById('val-error-alert');
      const errLabel = document.getElementById('val-error-text');

      if (!desc) {
         errPanel.classList.remove('hidden');
         errLabel.textContent = "Please offer a valid item description.";
         return;
      }
      if (isNaN(amountVal) || amountVal <= 0) {
         errPanel.classList.remove('hidden');
         errLabel.textContent = "Please offer a valid expense amount value.";
         return;
      }
      if (!date) {
         errPanel.classList.remove('hidden');
         errLabel.textContent = "Please specify transacted calendar date.";
         return;
      }

      errPanel.classList.add('hidden');

      // ------------------------------------------------------------------
      // BUDGET OVER-LIMIT RESTRICTION
      // Only enforce when adding a new expense (not editing an existing one).
      // ------------------------------------------------------------------
      if (!expenseEditId) {
        const currentTotal = expenses.reduce((sum, e) => sum + Number(e.amount), 0);
        if (profile.budget > 0 && (currentTotal + amountVal) > profile.budget) {
          const remaining = Math.max(profile.budget - currentTotal, 0);
          errPanel.classList.remove('hidden');
          errLabel.textContent = `🚫 Budget exceeded! You only have ${fmtRupee(remaining)} remaining of your ${fmtRupee(profile.budget)} limit. Reduce the amount or increase your budget in Settings.`;
          return;
        }
      }
      // ------------------------------------------------------------------

      const dataPayload = {
        desc,
        amount: amountVal,
        cat: selectedFormCategory,
        date
      };

      if (expenseEditId) {
        // Edit update operations
        if (isLocalDataSource) {
          expenses = expenses.map(e => e.id === expenseEditId ? { ...e, ...dataPayload } : e);
          saveLocalData();
          renderUI();
        } else {
          try {
            await updatePhpExpense({ id: expenseEditId, ...dataPayload });
            await fetchPhpExpenses();
          } catch (e) {
            console.error(e);
            alert("XAMPP network error updating record.");
          }
        }
      } else {
        // Add create operations
        if (isLocalDataSource) {
          const newId = Date.now();
          expenses.unshift({ id: newId, ...dataPayload });
          saveLocalData();
          renderUI();
        } else {
          try {
            await insertPhpExpense(dataPayload);
            await fetchPhpExpenses();
          } catch (e) {
            console.error(e);
            alert("XAMPP network error saving entry.");
          }
        }
      }

      closeExpenseModal();
    }

    function editExpenseEntry(id) {
      openExpenseModal(id);
    }

    async function deleteExpenseEntry(id) {
      if (confirm('Are you sure you want to delete this expense record?')) {
        if (isLocalDataSource) {
          expenses = expenses.filter(e => e.id !== id);
          saveLocalData();
          renderUI();
        } else {
          try {
            await deletePhpExpense(id);
            await fetchPhpExpenses();
          } catch (e) {
            console.error(e);
            alert("XAMPP endpoint network delete error.");
          }
        }
      }
    }


    // ------------------------------------------------------------------
    // 5. PROFILE CONTROLLER DIALOGS
    // ------------------------------------------------------------------
    function openProfileModal() {
      document.getElementById('fp-name').value = profile.name;
      document.getElementById('fp-email').value = profile.email;
      document.getElementById('fp-budget').value = profile.budget;
      
      document.getElementById('profile-error-alert').classList.add('hidden');
      
      const modal = document.getElementById('profile-modal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeProfileModal() {
      document.getElementById('profile-modal').classList.remove('flex');
      document.getElementById('profile-modal').classList.add('hidden');
    }

    async function handleProfileFormSave() {
      const name = document.getElementById('fp-name').value.trim();
      const email = document.getElementById('fp-email').value.trim();
      const budgetVal = parseFloat(document.getElementById('fp-budget').value);
      const errPanel = document.getElementById('profile-error-alert');
      const errLabel = document.getElementById('profile-error-text');

      if (!name) {
        errPanel.classList.remove('hidden');
        errLabel.textContent = "Please provide profile username.";
        return;
      }
      if (!email || !email.includes('@')) {
        errPanel.classList.remove('hidden');
        errLabel.textContent = "Please provide a valid email address.";
        return;
      }
      if (isNaN(budgetVal) || budgetVal < 0) {
        errPanel.classList.remove('hidden');
        errLabel.textContent = "Budget monthly ceiling must be a positive number.";
        return;
      }

      errPanel.classList.add('hidden');

      const updated = { name, email, budget: budgetVal };

      if (isLocalDataSource) {
        profile = updated;
        saveLocalData();
        renderUI();
      } else {
        try {
          await savePhpProfile(updated);
          await fetchPhpProfile();
        } catch (e) {
          console.error(e);
          alert("XAMPP network error saving profile changes.");
        }
      }

      closeProfileModal();
    }


    // ------------------------------------------------------------------
    // 6. VOICE RECOGNITION INTELLIGENT PARSER
    // ------------------------------------------------------------------
    function startVoiceCapture() {
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      if (!SpeechRecognition) {
        document.getElementById('voice-helper-hint').textContent = '⚠️ Voice Speech Recognition not supported in this browser. Try Google Chrome.';
        return;
      }

      const micBtn = document.getElementById('voice-mic-btn');
      if (recognitionInstance) {
        stopVoiceCapture();
        return;
      }

      try {
        const rec = new SpeechRecognition();
        recognitionInstance = rec;
        rec.lang = 'en-IN'; // Optimized to parse Indian-English contexts
        rec.interimResults = true;
        rec.continuous = false;

        rec.onstart = () => {
          micBtn.className = "w-11 h-11 bg-rose-500 border border-rose-600 text-white rounded-xl flex items-center justify-center listening-pulse cursor-pointer";
          micBtn.innerHTML = '<i data-lucide="mic-off" class="w-5 h-5"></i>';
          lucide.createIcons();
          document.getElementById('voice-helper-hint').textContent = '🔴 Listening... speak now';
        };

        rec.onresult = (event) => {
          const trans = Array.from(event.results).map(r => r[0].transcript).join(' ');
          document.getElementById('voice-helper-hint').innerHTML = `🎙 <i>"${trans}"</i>`;
          
          if (event.results[event.results.length - 1].isFinal) {
            parseAndPopulateVoice(trans);
          }
        };

        rec.onerror = (e) => {
          console.error(e);
          document.getElementById('voice-helper-hint').textContent = '⚠️ Couldn\'t capture audio. Please try speaking again.';
          stopVoiceCapture();
        };

        rec.onend = () => {
          stopVoiceCapture();
        };

        rec.start();
      } catch (e) {
        console.error(e);
        stopVoiceCapture();
      }
    }

    function stopVoiceCapture() {
      if (recognitionInstance) {
        try { recognitionInstance.stop(); } catch (err) {}
        recognitionInstance = null;
      }
      const micBtn = document.getElementById('voice-mic-btn');
      if (micBtn) {
        micBtn.className = "w-11 h-11 bg-slate-50 border border-slate-205 text-slate-600 rounded-xl flex items-center justify-center hover:bg-slate-100 transition-colors cursor-pointer";
        micBtn.innerHTML = '<i data-lucide="mic" class="w-5 h-5"></i>';
        lucide.createIcons();
      }
    }

    function parseAndPopulateVoice(text) {
      const normalized = text.toLowerCase();
      
      const categoryKeywords = {
        Food: ['food', 'lunch', 'dinner', 'breakfast', 'eat', 'restaurant', 'cafe', 'grocery', 'groceries', 'burger', 'pizza', 'tea', 'coffee', 'starbucks', 'maggi'],
        Transport: ['transport', 'auto', 'cab', 'uber', 'ola', 'taxi', 'bus', 'train', 'flight', 'metro', 'petrol', 'fuel', 'diesel', 'bike', 'car', 'fare'],
        Shopping: ['shopping', 'clothes', 'buy', 'tshirt', 'dress', 'shoes', 'amazon', 'flipkart', 'mall', 'headphones', 'watch', 'bag'],
        Health: ['health', 'hospital', 'doctor', 'medicine', 'medicines', 'pharmacy', 'clinic', 'tablet', 'checkup'],
        Entertainment: ['entertainment', 'movie', 'show', 'netflix', 'spotify', 'theatre', 'cinema', 'game', 'gaming', 'pub', 'party', 'concert'],
        Other: ['other', 'bill', 'rent', 'misc', 'recharge', 'gift', 'donation', 'fees']
      };

      let parsedCat = null;
      const words = normalized.split(/\s+/);
      
      // Match categories
      for (const [cat, keywords] of Object.entries(categoryKeywords)) {
        if (keywords.some(kw => words.some(w => w.includes(kw)))) {
          parsedCat = cat;
          break;
        }
      }

      // Match amount value
      let parsedAmount = null;
      for (const w of words) {
        const sanitized = w.replace(/[₹$,]/g, '');
        const num = parseFloat(sanitized);
        if (!isNaN(num) && num > 0) {
          parsedAmount = num;
          if (num < 100000) break; // bypass years
        }
      }

      // Match description
      const ignoredTerms = Object.values(categoryKeywords).flat();
      const descParts = text.split(/\s+/).filter(w => {
        const low = w.toLowerCase();
        const cleanVal = low.replace(/[₹$,]/g, '');
        if (!isNaN(parseFloat(cleanVal))) return false;
        if (ignoredTerms.includes(low)) return false;
        if (['rupees', 'rupee', 'rs', 'for', 'spent', 'on', 'paid', 'spent', ' rupees', 'a'].includes(low)) return false;
        return true;
      });

      const desc = descParts.join(' ').trim();

      if (desc) {
        document.getElementById('f-desc').value = desc;
      }
      if (parsedAmount) {
        document.getElementById('f-amount').value = parsedAmount;
      }
      if (parsedCat) {
        setCategorySelection(parsedCat);
      }

      if (desc || parsedAmount || parsedCat) {
        document.getElementById('voice-helper-hint').innerHTML = '✅ Filled! Please review and click save.';
      } else {
        document.getElementById('voice-helper-hint').innerHTML = '⚠️ Recognition failure. Please try saying: "Dinner 320 food"';
      }
    }


    // ------------------------------------------------------------------
    // 7. INITIALIZATION BLOCK
    // ------------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', () => {
      // Set correct current month-year context on load
      const now = new Date();
      document.getElementById('month-label').textContent = now.toLocaleString('en-US', { month: 'long', year: 'numeric' });
      
      // Setup Lucide icons wrapper
      lucide.createIcons();

      // Begin loading datasets from LocalStorage or XAMPP depending on hostname
      loadAllData();
    });

  </script>

</body>
</html>