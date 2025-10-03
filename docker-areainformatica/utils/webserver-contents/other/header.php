<?php
// Copyright (C) 2015 Antonio Daniele Gialluisi

// This file is part of "Area informatica"

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program. If not, see <https://www.gnu.org/licenses/>.
?>
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
