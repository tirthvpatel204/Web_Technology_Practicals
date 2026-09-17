<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <div class="header">
        <div class="logo">✦</div>
        <h1>Event <span>Registration</span></h1>
        <p>Register your details and secure your participation.</p>
    </div>

    <div class="card">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                ✓ Registration submitted successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                ✕ <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-header">
            <h2>Participant Details</h2>
            <p>Fill in the information below carefully.</p>
        </div>

        <form action="process.php" method="POST">

            <div class="form-grid">

                <div class="field">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your full name"
                        required
                    >
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        required
                    >
                </div>

                <div class="field">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="10 digit mobile number"
                        maxlength="10"
                        required
                    >
                </div>

                <div class="field">
                    <label>Course</label>
                    <select name="course" required>
                        <option value="">Select your course</option>
                        <option>B.Tech AIML</option>
                        <option>B.Tech CSE</option>
                        <option>BCA</option>
                        <option>MCA</option>
                        <option>BBA</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="field full">
                    <label>Message</label>
                    <textarea
                        name="message"
                        placeholder="Write your message..."
                        required
                    ></textarea>
                </div>

            </div>

            <button class="submit-btn" type="submit">
                Submit Registration →
            </button>

        </form>

        <div class="links">
            <a href="records.php">View Registered Records</a>
        </div>

    </div>

</div>

</body>
</html>