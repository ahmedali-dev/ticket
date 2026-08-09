# Routes Guide / دليل المسارات

Quick reference for how routing works in this app — what each URL does, what protects it, and how the pieces fit together. English first, Arabic below it.

---

## 🇬🇧 English

### What this app actually does

It's a Laravel app that handles two main things for a company: **support tickets** and a **training center**. Users log in through Breeze, can open/reply to/close tickets, and can work through training courses that are broken into modules and chapters (with video playback, chapter jump-to-timestamp, the works). On top of that there's basic admin stuff — managing users and the companies they belong to.

Every route that needs a logged-in user sits behind two middleware:

- `auth` — has to be logged in, standard Breeze stuff
- `active` — and their account can't be disabled. If someone's status flips to disabled mid-session, they get bounced out on their next request (see `EnsureUserIsActive`)

### Public stuff (no login needed)

| Method | URL | Name | What it does |
|---|---|---|---|
| GET | `/` | — | Landing page |
| GET | `/lang/{locale}` | `lang.switch` | Swaps the language, saves it to session, sends you back where you were |

### Tickets

| Method | URL | Name | What it does |
|---|---|---|---|
| GET | `/ticket` | `ticket.index` | The ticket list |
| GET | `/ticket/create` | `ticket.create` | Form for a new ticket |
| POST | `/ticket` | `ticket.store` | Saves it |
| GET | `/ticket/{ticket}/reply` | `ticket.reply` | Opens a ticket + its thread |
| POST | `/ticket/search` | `ticket.search` | Searches tickets |
| POST | `/ticket/{ticket}/close` | `ticket.update` | Closes it |
| POST | `/reply/{ticket}` | `reply.store` | Adds a reply |

### Training center

| Method | URL | Name | What it does |
|---|---|---|---|
| GET | `/training/{training}/{module}` | `training.module_show` | Opens a specific module inside a course |
| resource | `/training` | `training.*` | Standard CRUD — list, create, store, edit, update, delete |
| POST | `/module` | `module.store` | Adds a module (with the video file) to a course |
| POST | `/chapter` | `chapter.store` | Adds a chapter to a module — just needs a title, module id, and optionally a timestamp |

### Users

| Method | URL | Name | What it does |
|---|---|---|---|
| GET | `/users` | `users.index` | User list |
| GET | `/users/add` | `users.create` | New-user form |
| POST | `/users` | `users.store` | Saves the new user |
| PATCH | `/users/{user}` | `users.toggle-status` | Flips a user between active/disabled |

### Companies

| Method | URL | Name | What it does |
|---|---|---|---|
| GET | `/company` | `company.index` | Company list |
| POST | `/company` | `company.store` | Saves a new company |
| GET | `/company/{company}/edit` | `company.edit` | Edit form |
| PUT | `/company/{company}` | `company.update` | Saves changes |
| GET | `/company/{company}` | `company.show` | Company page — details + the users under it |

### Account stuff

| Method | URL | Name | Middleware | What it does |
|---|---|---|---|---|
| GET | `/dashboard` | `dashboard` | `auth`, `verified` | Main dashboard after login |
| GET | `/profile` | `profile.edit` | `auth` | Edit your own profile |
| PATCH | `/profile` | `profile.update` | `auth` | Save profile changes |
| DELETE | `/profile` | `profile.destroy` | `auth` | Delete your account |

Login, register, password reset — all that's handled by Breeze in `routes/auth.php`, not touched here.

### The data model, roughly

- **Users**: name, email, password, `status` (active/disabled), and optionally belong to a `company`
- **Companies**: name, phone, `status` (on/off)
- **Trainings → Modules → Chapters**: a training has modules (each one has its own video), and each module has chapters — just a title plus a timestamp (`m:ss`) into that module's video
- **Tickets → Replies**: pretty much what it sounds like

### Running it locally

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### Stuff that's still missing / rough edges

- No edit page for users yet — you can add and disable them, but not edit name/email/company after the fact
- The Add/Edit dialogs use the newer `command`/`commandfor` HTML API, which right now only works in Chrome/Edge (135+). Safari and Firefox users will just see nothing happen when they click the buttons until there's a JS fallback using plain `showModal()`/`close()`
- `companies.phone` is stored as an `integer`, which is a mistake — real phone numbers can start with 0 and can be longer than an int can hold. Should be a `string`
- No delete option anywhere yet, just disable/toggle
- Pagination is wired up but not really styled/tested beyond the basics
- No search or filtering on the users/companies lists
- Chapter thumbnails get generated in the browser every time the page loads, which works but is wasteful — should really be done once server-side with ffmpeg and cached
- Zero automated tests so far
- No real roles/permissions — right now it's just active vs disabled, no concept of "admin"
- No email notifications (e.g. someone replies to your ticket, or your account gets disabled)
- Arabic layout has translated text but hasn't been checked for actual RTL rendering issues
- No API docs, but there's nothing exposed as an API yet either

---
---

## 🇸🇦 العربية

### إيش يسوي التطبيق فعليًا

تطبيق Laravel يتعامل مع شيئين رئيسيين للشركة: **تذاكر الدعم الفني** و**مركز تدريب**. المستخدمون يسجّلون الدخول عبر Breeze، يقدرون يفتحون تذاكر ويردّون عليها ويقفلونها، وكمان يقدرون يتابعون دورات تدريبية مقسّمة لوحدات وفصول (مع تشغيل فيديو، والقفز لفصل معيّن بالضغط عليه، إلخ). فوق هذا كله فيه أدوات إدارية بسيطة — إدارة المستخدمين والشركات اللي يتبعون لها.

أي مسار يحتاج تسجيل دخول يمرّ على اثنين Middleware:

- `auth` — لازم يكون مسجّل دخول، شي عادي من Breeze
- `active` — وحسابه مو معطّل. لو حد عطّل حسابه وهو داخل بالجلسة، بينطرد بأول طلب جاي (شوف `EnsureUserIsActive`)

### المسارات العامة (بدون تسجيل دخول)

| الطريقة | الرابط | الاسم | إيش يسوي |
|---|---|---|---|
| GET | `/` | — | الصفحة الرئيسية |
| GET | `/lang/{locale}` | `lang.switch` | يبدّل اللغة، يحفظها بالجلسة، ويرجّعك لنفس المكان |

### التذاكر

| الطريقة | الرابط | الاسم | إيش يسوي |
|---|---|---|---|
| GET | `/ticket` | `ticket.index` | قائمة التذاكر |
| GET | `/ticket/create` | `ticket.create` | نموذج تذكرة جديدة |
| POST | `/ticket` | `ticket.store` | يحفظها |
| GET | `/ticket/{ticket}/reply` | `ticket.reply` | يفتح التذكرة مع الردود عليها |
| POST | `/ticket/search` | `ticket.search` | بحث بالتذاكر |
| POST | `/ticket/{ticket}/close` | `ticket.update` | يقفل التذكرة |
| POST | `/reply/{ticket}` | `reply.store` | يضيف رد |

### مركز التدريب

| الطريقة | الرابط | الاسم | إيش يسوي |
|---|---|---|---|
| GET | `/training/{training}/{module}` | `training.module_show` | يفتح وحدة معيّنة داخل دورة |
| resource | `/training` | `training.*` | عمليات CRUD القياسية — عرض، إنشاء، حفظ، تعديل، تحديث، حذف |
| POST | `/module` | `module.store` | يضيف وحدة (مع ملف الفيديو) لدورة |
| POST | `/chapter` | `chapter.store` | يضيف فصل لوحدة — بس يحتاج عنوان ومعرف الوحدة، والمدة اختيارية |

### المستخدمون

| الطريقة | الرابط | الاسم | إيش يسوي |
|---|---|---|---|
| GET | `/users` | `users.index` | قائمة المستخدمين |
| GET | `/users/add` | `users.create` | نموذج مستخدم جديد |
| POST | `/users` | `users.store` | يحفظ المستخدم الجديد |
| PATCH | `/users/{user}` | `users.toggle-status` | يفعّل/يعطّل مستخدم |

### الشركات

| الطريقة | الرابط | الاسم | إيش يسوي |
|---|---|---|---|
| GET | `/company` | `company.index` | قائمة الشركات |
| POST | `/company` | `company.store` | يحفظ شركة جديدة |
| GET | `/company/{company}/edit` | `company.edit` | نموذج التعديل |
| PUT | `/company/{company}` | `company.update` | يحفظ التعديلات |
| GET | `/company/{company}` | `company.show` | صفحة الشركة — التفاصيل + المستخدمين اللي تحتها |

### الحساب الشخصي

| الطريقة | الرابط | الاسم | Middleware | إيش يسوي |
|---|---|---|---|---|
| GET | `/dashboard` | `dashboard` | `auth`, `verified` | لوحة التحكم بعد الدخول |
| GET | `/profile` | `profile.edit` | `auth` | تعديل ملفك الشخصي |
| PATCH | `/profile` | `profile.update` | `auth` | حفظ التعديلات |
| DELETE | `/profile` | `profile.destroy` | `auth` | حذف حسابك |

تسجيل الدخول والتسجيل واستعادة كلمة المرور — كلها يتكفّل فيها Breeze داخل `routes/auth.php`، ما لها علاقة بهذا الملف.

### نموذج البيانات، تقريبًا

- **المستخدمون**: الاسم، الإيميل، كلمة المرور، `status` (مفعّل/معطّل)، وممكن يتبعون لشركة أو لا
- **الشركات**: الاسم، رقم الجوال، `status` (شغال/متوقف)
- **الدورات ← الوحدات ← الفصول**: الدورة فيها وحدات (كل وحدة لها فيديو خاص فيها)، وكل وحدة فيها فصول — بس عنوان زائد توقيت (`m:ss`) داخل فيديو الوحدة
- **التذاكر ← الردود**: بالضبط زي ما يبدو من الاسم

### تشغيله عندك

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### أشياء لسه ناقصة / محتاجة شغل

- ما فيه صفحة تعديل للمستخدمين لحد الآن — تقدر تضيف وتعطّل، بس ما تقدر تعدّل الاسم أو الإيميل أو الشركة بعدين
- نوافذ الإضافة/التعديل تستخدم API الجديدة `command`/`commandfor`، اللي حاليًا شغالة بس على Chrome وEdge (135+). مستخدمي Safari وFirefox راح يضغطون الزر وما يصير شي لين نضيف حل بديل بجافاسكريبت عادي (`showModal()`/`close()`)
- عمود `companies.phone` مخزّن كـ `integer`، وهذا خطأ — أرقام الجوال ممكن تبدأ بصفر وتكون أطول من اللي يقدر يخزّنه الـ int. لازم يصير `string`
- ما فيه خيار حذف في أي مكان لحد الآن، بس تفعيل/تعطيل
- ترقيم الصفحات (Pagination) موصول بس ما تم تنسيقه أو اختباره كويس
- ما فيه بحث أو تصفية بقوائم المستخدمين والشركات
- الصور المصغّرة للفصول تتولّد من المتصفح كل مرة تفتح الصفحة، وهذا يشتغل بس فيه هدر — الأفضل تتولّد مرة وحدة من السيرفر بـ ffmpeg وتتخزّن
- صفر اختبارات تلقائية لحد الآن
- ما فيه نظام صلاحيات حقيقي — حاليًا بس مفعّل أو معطّل، ما فيه مفهوم "مدير"
- ما فيه إشعارات إيميل (مثلاً حد يرد على تذكرتك، أو يتعطّل حسابك)
- الواجهة العربية فيها ترجمة للنصوص بس ما تم التأكد من مشاكل الاتجاه (RTL) الفعلية
- ما فيه توثيق API، بس أصلاً ما فيه شي متاح كـ API لحد الآن
