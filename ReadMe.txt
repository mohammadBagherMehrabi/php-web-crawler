#Web Crawler for Keyword Analysis

A PHP script to crawl a website, search for a keyword, and save results.


#How to use

1-create (if it doesn't exist) a config.json file in the root of the project with the following structure:

    {
        "url": "https://www.shahrekhabar.com/",
        "word": "اقتصاد"
    }

   Replace the URL and the keyword as needed.

2- run the crawler:

    php index.php


#Note

Sometimes words may be used in parts outside the main text of the content,
such as the sidebar, header and other fixed parts of the page,
which could result in higher counts than expected.



