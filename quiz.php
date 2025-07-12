<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skin Quiz</title>
    <style>
        body {
            font-family: Modern No. 20;
            background-color: #f4f4f4;
            background-image:url(bg.jpg);
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #0b1957;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            margin-top: 0;
        }

        .question {
            margin-bottom: 15px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #0b1957;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Skin Quiz</h1>
    </div>

    <div class="container">
    
        <h2>Tell us about your skin</h2>
        <form method="post" action="quiz_outcome.php">
            <div class="question">
                <label>How would you describe your skin type?</label><br>
                <input type="radio" name="skin_type" value="dry"> Dry<br>
                <input type="radio" name="skin_type" value="oily"> Oily<br>
                <input type="radio" name="skin_type" value="combination"> Combination<br>
                <input type="radio" name="skin_type" value="sensitive"> Sensitive<br>
            </div>
            <div class="question">
                <label>Do you have any skin concerns?</label><br>
                <input type="checkbox" name="concern_acne" value="acne"> Acne<br>
                <input type="checkbox" name="concern_wrinkles" value="wrinkles"> Wrinkles<br>
                <input type="checkbox" name="concern_redness" value="redness"> Redness<br>
                <input type="checkbox" name="concern_dryness" value="dryness"> Dryness<br>
                <br>
                
            </div>
            <div class="question">
                <label>How would you describe your skin texture?</label><br>
                <input type="checkbox" name="texture_smooth" value="smooth">Smooth and fine<br>
                <input type="checkbox" name="texture_rough" value="slightly rough">Slightly rough  <br>
                <input type="checkbox" name="texture_uneven" value="uneven">Uneven <br>
                <input type="checkbox" name="texture_coarse" value="coarse">Coarse and rough<br>
            </div>
            <div class="question">
                <label>How would you describe your skins oil production?</label><br>
                <input type="checkbox" name="oilproduction_dry" value="dry"> Dry, with little to no oiliness<br>
                <input type="checkbox" name="oilproduction_balanced" value="balanced">Balanced, with minimal oiliness<br>
                <input type="checkbox" name="oilproduction_oily" value="oily"> Very oily, especially in the T-zone (forehead, nose, chin) <br>
                <input type="checkbox" name="oilproduction_tzone" value="tzone">Somewhat oily, but mostly in the T-zone <br>
            </div>
            <?php
            include 'config.php';
            $dynamic_questions = [];
            $dynamic_result = $conn->query("SELECT concern_text FROM faq_concerns ORDER BY frequency DESC LIMIT 3");
            while ($row = $dynamic_result->fetch_assoc()) {
                $dynamic_questions[] = $row['concern_text'];
            }
            ?>
            <?php if (!empty($dynamic_questions)): ?>
            <div class="question">
                <label>Other users have mentioned these concerns. Do you also experience any of the following?</label><br>
                <?php foreach ($dynamic_questions as $idx => $concern): ?>
                    <input type="checkbox" name="dynamic_concern_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($concern); ?>">
                    <?php echo htmlspecialchars($concern); ?><br>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <label for="concern_text">Briefly describe your concerns (optional):</label>
            <select id="speechLang" style="margin-left:10px;">
                <option value="en-US">English (US)</option>
                <option value="en-IN">English (India)</option>
                <option value="hi-IN">Hindi</option>
                <option value="fr-FR">French</option>
                <option value="es-ES">Spanish</option>
                <option value="de-DE">German</option>
                <option value="zh-CN">Chinese (Mandarin)</option>
            </select>
            <button type="button" onclick="startDictation()" style="margin-left:10px;">🎤 Speak</button><br>
            <textarea id="concern_text" name="concern_text" rows="3" cols="50" placeholder="Describe your skin concerns here..."></textarea><br>
            <script>
            function startDictation() {
                if ('webkitSpeechRecognition' in window) {
                    var recognition = new webkitSpeechRecognition();
                    var lang = document.getElementById('speechLang').value;
                    recognition.lang = lang;

                    // Autofill prompt space when speech starts
        document.getElementById('concern_text').placeholder = "Listening... Please speak now.";
        recognition.onresult = function(event) {
            document.getElementById('concern_text').value = event.results[0][0].transcript;
            document.getElementById('concern_text').placeholder = "Describe your skin concerns here...";
        };
        recognition.onend = function() {
            document.getElementById('concern_text').placeholder = "Describe your skin concerns here...";
        };
                    recognition.start();
                } else {
                    alert('Speech recognition not supported in this browser.');
                }
            }
            </script>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
