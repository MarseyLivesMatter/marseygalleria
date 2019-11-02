<?php

class BanWordsInfo extends ExtensionInfo
{
    public const KEY = "ban_words";

    public $key = self::KEY;
    public $name = "Comment Word Ban";
    public $url = self::SHIMMIE_URL;
    public $authors = self::SHISH_AUTHOR;
    public $license = self::LICENSE_GPLV2;
    public $description = "For stopping spam and other comment abuse";
    public $documentation =
"Allows an administrator to ban certain words
from comments. This can be a very simple but effective way
of stopping spam; just add \"viagra\", \"porn\", etc to the
banned words list.
<p>Regex bans are also supported, allowing more complicated
bans like <code>/http:.*\.cn\//</code> to block links to
chinese websites, or <code>/.*?http.*?http.*?http.*?http.*?/</code>
to block comments with four (or more) links in.
<p>Note that for non-regex matches, only whole words are
matched, eg banning \"sex\" would block the comment \"get free
sex call this number\", but allow \"This is a photo of Bob
from Essex\"";
}
