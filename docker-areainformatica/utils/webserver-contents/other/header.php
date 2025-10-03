<header>
    <?php
    if (!isset($NOACCOUNTINFO)) {
        require($_SERVER['DOCUMENT_ROOT'] . "/other/account_opts.html.php");
    }
    if (!isset($NOHEADER)):
    ?>
    <div><h1>Area Informatica</h1></div>

    <?php
    endif;
    ?>
		<p>
	    <?php if (!isset($NOMENUBAR)): ?>
			<?php if (isset($LOCATION) && $LOCATION === "HOME"): ?>
                <b>Home</b>
            <?php else: ?>
                <a href = "/">Home</a>
            <?php endif; ?>

            <?php if (isset($LOCATION) && $LOCATION === "BLOG"): ?>
                <b>Blog</b>
            <?php else: ?>
                <a href = "/other/blogindex.html.php">Blog</a>
            <?php endif; ?>

            <?php if (isset($LOCATION) && $LOCATION === "LESSONS"): ?>
                <b>Lezioni</b>
            <?php else: ?>
                <a href = "/subjects/subjects_list_page.html.php">Lezioni</a>
            <?php endif; ?>

            <?php if (isset($LOCATION) && $LOCATION === "INFOPAGE"): ?>
                <b>Contatti</b>
            <?php else: ?>
                <a href = "/other/aboutpage.html.php">Contatti</a>
            <?php endif; ?>
		<?php endif; ?>
    </p>
</header>
