<?php
$risk_score = null;
$risk_level = "";
$badge_color = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = $_POST['age_group'];
    $gender = $_POST['gender'];
    $education = $_POST['education'];
    $usage = $_POST['usage_freq'];

    // Intercept & Model Coefficients
    $intercept = 1.5534;
    
    $beta_age = 0;
    if ($age == "25-34") $beta_age = 1.1371;
    elseif ($age == "35-44") $beta_age = 0.6875;
    elseif ($age == "45+") $beta_age = 0.7905;
    elseif ($age == "Under 18") $beta_age = -0.7012;

    $beta_gender = ($gender == "Male") ? -0.7209 : 0;

    $beta_edu = 0;
    if ($education == "Degree") $beta_edu = -2.1615;
    elseif ($education == "Diploma") $beta_edu = -1.9187;
    elseif ($education == "Postgraduate") $beta_edu = -1.9790;
    elseif ($education == "SHS") $beta_edu = -1.5752;

    $beta_usage = 0;
    if ($usage == "Rarely") $beta_usage = 0.3919;
    elseif ($usage == "Several times a week") $beta_usage = -0.3077;
    elseif ($usage == "Weekly") $beta_usage = 0.3706;

    $z = $intercept + $beta_age + $beta_gender + $beta_edu + $beta_usage;
    $probability = (1 / (1 + exp(-$z))) * 100;
    $risk_score = round($probability, 1);

    if ($risk_score < 35) {
        $risk_level = "Low Vulnerability Risk";
        $badge_color = "#28a745";
    } elseif ($risk_score <= 55) {
        $risk_level = "Moderate Vulnerability Risk";
        $badge_color = "#ffc107";
    } else {
        $risk_level = "High Vulnerability Risk";
        $badge_color = "#dc3545";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoMo Fraud Risk Checker</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px 10px; }
        .card { max-width: 550px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { background-color: #0d6efd; color: #ffffff; text-align: center; padding: 20px; }
        .card-header h2 { margin: 0; font-size: 22px; }
        .card-body { padding: 25px; }
        .form-group { margin-bottom: 18px; }
        label { font-weight: bold; display: block; margin-bottom: 6px; font-size: 14px; color: #333; }
        select { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
        .btn { width: 100%; background-color: #0d6efd; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; transition: 0.2s; }
        .btn:hover { background-color: #0b5ed7; }
        .result-box { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 2px solid #eeeeee; }
        .score { font-size: 42px; font-weight: bold; margin: 10px 0; color: #222; }
        .badge { display: inline-block; padding: 8px 16px; border-radius: 20px; color: white; font-weight: bold; font-size: 14px; }
        .alert { background-color: #e7f3fe; border-left: 4px solid #0d6efd; color: #0c5460; padding: 12px; margin-top: 20px; text-align: left; font-size: 13px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h2>🛡️ MoMo Fraud Risk Checker</h2>
        <small style="opacity: 0.8;">Empirical Model Powered by Thesis Research Data</small>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="form-group">
                <label>Age Group</label>
                <select name="age_group" required>
                    <option value="Under 18">Under 18</option>
                    <option value="18-24">18 - 24 years</option>
                    <option value="25-34" selected>25 - 34 years</option>
                    <option value="35-44">35 - 44 years</option>
                    <option value="45+">45+ years</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender" required>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                </select>
            </div>

            <div class="form-group">
                <label>Educational Level</label>
                <select name="education" required>
                    <option value="Basic">Basic / JHS</option>
                    <option value="SHS">Senior High School (SHS)</option>
                    <option value="Diploma">Diploma </option>
                    <option value="Degree" selected>Bachelor's Degree</option>
                    <option value="Postgraduate">Postgraduate Degree</option>
                </select>
            </div>

            <div class="form-group">
                <label>Mobile Money Usage Frequency</label>
                <select name="usage_freq" required>
                    <option value="Daily">Daily</option>
                    <option value="Several times a week">Several times a week</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Rarely">Rarely</option>
                </select>
            </div>

            <button type="submit" class="btn">Calculate Fraud Risk</button>
        </form>

        <?php if ($risk_score !== null): ?>
            <div class="result-box">
                <span style="color: #666; font-weight: 500;">Your Calculated Risk Vulnerability:</span>
                <div class="score"><?php echo $risk_score; ?>%</div>
                <span class="badge" style="background-color: <?php echo $badge_color; ?>; <?php echo ($badge_color == '#ffc107') ? 'color: #000;' : ''; ?>">
                    <?php echo $risk_level; ?>
                </span>
                
                <div class="alert">
                    <strong>💡 Recommendation:</strong>
                    <?php if ($risk_score > 50): ?>
                        Always confirm recipient names on prompt overlays before entering your PIN. Be wary of unsolicited calls claiming wrong transaction reversals.
                    <?php else: ?>
                        Maintain your cautious transaction habits and keep your MoMo PIN private.
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>