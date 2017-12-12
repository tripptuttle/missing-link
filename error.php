<section id="error" class="message">
    <h1>Error: <?php echo http_response_code(); ?></h1>
    <?php if (isset($errorMsg)) { echo "<p>Error Message Returned: $errorMsg"; } ?>
    <?php if (isset($e)) { echo "<p>PHP Error: $e"; } ?>
</section>
