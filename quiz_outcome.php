<?php
session_start();

// Example database connection
$conn = new mysqli("localhost", "root", "", "skin_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Check if quiz data is available (typically this would be stored in the session or processed from the POST request)
if (!isset($_POST['skin_type'])) {
    header('Location: quiz.php');
    exit();
}

// Store quiz results
$skin_type = $_POST['skin_type'];
$concerns = [
    'acne' => isset($_POST['concern_acne']),
    'wrinkles' => isset($_POST['concern_wrinkles']),
    'redness' => isset($_POST['concern_redness']),
    'dryness' => isset($_POST['concern_dryness']),
];

$texture = $_POST['texture_smooth'] ?? $_POST['texture_rough'] ?? $_POST['texture_uneven'] ?? $_POST['texture_coarse'] ?? '';
    $oil_production = $_POST['oilprod_dry'] ?? $_POST['oilprod_balanced'] ?? $_POST['oilprod_oily'] ?? $_POST['oilprod_tzone'] ?? '';

// Determine recommendations based on quiz answers
$recommendations = [];

if ($skin_type == 'dry') {
    $recommendations[] = 'Moisturizer for Dry Skin';
}

if ($skin_type == 'oily' || $concerns['acne']) {
    $recommendations[] = 'Oil-free Cleanser';
}

if ($skin_type == 'sensitive' || $concerns['redness']) {
    $recommendations[] = 'Soothing Sunscreen';
}

if ($concerns['wrinkles']) {
    $recommendations[] = 'Anti-Aging Serum';
}
if($texture == 'coarse'|| $oil_production == 'oily')
{
    $recommendations[]='Exfoliating Scrub';
}
if ($texture == 'uneven') {
    $recommendations[] = 'Vitamin C Serum';
}

if ($texture == 'rough') {
    $recommendations[] = 'Niacinamide Serum';
}

if ($skin_type == 'combination' && $oil_production == 'tzone') {
    $recommendations[] = 'Balancing Toner';
}

if ($concerns['dryness']) {
    $recommendations[] = 'Hydrating Sheet Mask';
}

if ($skin_type == 'normal' && $oil_production == 'balanced') {
    $recommendations[] = 'Gentle Daily Cleanser';
}
$routine = [
    'Cleanser' => 'Gentle foaming cleanser suitable for your skin type.',
    'Toner' => 'Alcohol-free toner to balance pH and hydrate.',
    'Serum' => 'Targeted serum for your concerns (e.g., acne, wrinkles).',
    'Moisturizer' => 'Choose a moisturizer that matches your skin’s hydration needs.',
    'Sunscreen' => 'Use SPF 30+ daily, even indoors.',
];




// --- MCP/AI Integration ---
// Collect all user responses
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
$concern_text = isset($_POST['concern_text']) ? trim($_POST['concern_text']) : '';
$dynamic_concerns = [];
foreach ($_POST as $key => $value) {
    if (strpos($key, 'dynamic_concern_') === 0) {
        $dynamic_concerns[] = $value;
    }
}

// Prepare data for MCP/AI server
$user_data = [
    'skin_type' => $skin_type,
    'concerns' => array_keys(array_filter($concerns)),
    'dynamic_concerns' => $dynamic_concerns,
    'text_concern' => $concern_text
];

// Send to MCP/AI server (example endpoint)
$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($user_data),
        'timeout' => 10
    ],
];
$context  = stream_context_create($options);
$ai_response = @file_get_contents('http://localhost:8000/analyze_skin', false, $context);
$ai_result = $ai_response ? json_decode($ai_response, true) : null;
// --- End MCP/AI Integration ---



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Outcome</title>
    <style>
        body {
    font-family: Modern No. 20;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

.header {
    background-color: #0b1957;
    color: white;
    padding: 15px;
    text-align: center;
    width: 100%;
}

.container {
    width: 80%;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    margin-top: 0;
}

.recommendations {
    list-style-type: none;
    padding: 0;
    width: 100%;
}

.recommendations li {
    background-color: #f4f4f4;
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.learn-more {
    margin-top: 20px;
    background-color: #eef;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
}

.learn-more h3 {
    margin-top: 0;
}

.learn-more p {
    line-height: 1.6;
}

.learn-more ul {
    list-style-type: disc;
    margin-left: 20px;
}

.learn-more ul li {
    margin-bottom: 10px;
}

.btn-back {
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 10px;
    background-color: #0b1957;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.image-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: 30px;
    width: 100%;
}

.image-section img {
    max-width: 48%;
    margin: 10px 0;
    border-radius: 8px;
}
.image-section .text-item {
    max-width: 48%;
    margin: 10px 0;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.image-section .text-item h4 {
    color: #0b1957;
}

.text-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    margin-top: 30px;
}

.text-section .text-item {
    background-color: #ffffff;
    border-radius: 8px;
    margin: 15px 0;
    padding: 15px;
    width: 80%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.text-section .text-item h4 {
    color: #0b1957;
}

.text-section .text-item p {
    line-height: 1.6;
}

    </style>
</head>
<body>
    <div class="header">
        <h1>Your Skincare Recommendations</h1>
    </div>

    <div class="container">
    <?php if (!empty($ai_result['solutions'])): ?>
        <div class="ai-solutions" style="background-color:#e8f5e9; border-left:5px solid #388e3c; padding:15px; margin-bottom:20px; border-radius:5px;">
            <h3>Personalized Solutions</h3>
            <ul>
                <?php foreach ($ai_result['solutions'] as $sol): ?>
                    <li><?php echo htmlspecialchars($sol); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (!empty($ai_result['routine'])): ?>
        <div class="ai-routine" style="background-color:#e3f2fd; border-left:5px solid #1976d2; padding:15px; margin-bottom:20px; border-radius:5px;">
            <h3>Recommended Routine</h3>
            <ol>
                <?php foreach ($ai_result['routine'] as $step): ?>
                    <li><?php echo htmlspecialchars($step); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endif; ?>
    <h2>Based on your quiz answers, we recommend the following products:</h2>
    <ul class="recommendations">
        <?php foreach ($recommendations as $item): ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Learn More About Skincare Section -->
    <div class="learn-more">
        <h3>Learn More About Skincare</h3>
        <p>Here are some tips on how to take care of your skin and how to use the recommended products effectively:</p>
        <ul>
            <li><strong>Moisturizer:</strong> Apply twice daily after cleansing. Make sure to choose one that's appropriate for your skin type, and massage gently in circular motions.</li>
            <li><strong>Oil-Free Cleanser:</strong> Use it in the morning and evening to help control oil production. Avoid over-washing, as it can lead to dryness and irritation.</li>
            <li><strong>Soothing Sunscreen:</strong> Always apply sunscreen during the day, even indoors. Reapply every two hours if you’re outdoors for an extended period.</li>
            <li><strong>Anti-Aging Serum:</strong> Use it at night after cleansing and before moisturizing to target wrinkles and fine lines.</li>
            <li><strong>Exfoliating Scrub:</strong> Use once or twice a week to remove dead skin cells. Be gentle and avoid scrubbing too hard to prevent irritation.</li>
        </ul>
    </div>

    <!-- Image Section -->
    <div class="image-section">
        <img src="uploads\Glow Guide.jpeg" alt="Skincare Image 4">
        
        
    </div>
</div>
    <!-- Text Section -->
    <div class="text-section">
        <div class="text-item">
            <h4>Why Moisturizers are Essential</h4>
            <p>Moisturizers are important for replenishing skin hydration. Dry skin can cause discomfort, irritation, and premature aging, which is why it’s essential to use a good moisturizer.</p>
        </div>
        <div class="text-item">
            <h4>Understanding Oil-Free Cleansers</h4>
            <p>Oil-free cleansers help remove excess oil and prevent clogged pores, making them ideal for those with oily or acne-prone skin.</p>
        </div>
    </div>

        <a href="home.php" style="display:block; text-align:center; margin-top:20px; padding:10px; background-color:#0b1957; color:white; text-decoration:none; border-radius:5px;">Back to Home</a>
    </div>
</body>
</html>

<?php
// Database connection (assuming $conn is your connection variable)
// Save AI/MCP solutions and routine to user_concerns and faq_concerns
if (!empty($ai_result)) {
    $solutions_text = isset($ai_result['solutions']) ? implode('; ', $ai_result['solutions']) : '';
    $routine_text = isset($ai_result['routine']) ? implode('; ', $ai_result['routine']) : '';
    if ($user_id && ($solutions_text || $routine_text)) {
        // Save to user_concerns
        $stmt = $conn->prepare("INSERT INTO user_concerns (user_id, concern_text) VALUES (?, ?) ");
        $concern_save = trim($solutions_text . ' ' . $routine_text);
        $stmt->bind_param("is", $user_id, $concern_save);
        $stmt->execute();
        $stmt->close();
        // Save/update in faq_concerns
        if ($solutions_text) {
            $stmt = $conn->prepare("INSERT INTO faq_concerns (concern_text, frequency) VALUES (?, 1) ON DUPLICATE KEY UPDATE frequency = frequency + 1");
            $stmt->bind_param("s", $solutions_text);
            $stmt->execute();
            $stmt->close();
        }
        if ($routine_text) {
            $stmt = $conn->prepare("INSERT INTO faq_concerns (concern_text, frequency) VALUES (?, 1) ON DUPLICATE KEY UPDATE frequency = frequency + 1");
            $stmt->bind_param("s", $routine_text);
            $stmt->execute();
            $stmt->close();
        }
    }
}
// Save prompt response (concern_description) to user_concerns and faq_concerns
// Save prompt response (concern_description) to user_concerns and faq_concerns
$concern_text = isset($_POST['concern_text']) ? trim($_POST['concern_text']) : '';
if ($user_id && !empty($concern_text)) {
    $stmt = $conn->prepare("INSERT INTO user_concerns (user_id, concern_text) VALUES (?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("is", $user_id, $concern_text);
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO faq_concerns (concern_text, frequency) VALUES (?, 1) ON DUPLICATE KEY UPDATE frequency = frequency + 1");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $concern_text);
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }
    $stmt->close();
}
?>
