<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;

class CourtsApiCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $courtsTable = TableRegistry::getTableLocator()->get('Courts');
        $link = 'https://www.courtlistener.com/api/rest/v3/courts/?format=json&page=1';

        while (true) {
            $courtsApi = $this->curl($link);

            foreach ($courtsApi->results as $courtApi) {
                $court = $courtsTable->find()
                ->where(['id' => $courtApi->id])->first();

                if (!$court) {
                    $court = $courtsTable->newEntity();
                    $court->id = $courtApi->id;
                }
                $court->pacer_court_id = $courtApi->pacer_court_id;
                $court->pacer_has_rss_feed = $courtApi->pacer_has_rss_feed;
                $court->fjc_court_id = $courtApi->fjc_court_id;
                $court->date_modified = $courtApi->date_modified;
                $court->in_use = $courtApi->in_use;
                $court->has_opinion_scraper = $courtApi->has_opinion_scraper;
                $court->has_oral_argument_scraper = $courtApi->has_oral_argument_scraper;
                $court->position = $courtApi->position;
                $court->citation_string = $courtApi->citation_string;
                $court->short_name = $courtApi->short_name;
                $court->full_name = $courtApi->full_name;
                $court->url = $courtApi->url;
                $court->start_date = $courtApi->start_date;
                $court->end_date = $courtApi->end_date;
                $court->jurisdiction = $courtApi->jurisdiction;

                $courtsTable->save($court);
            }

            if ( $courtsApi->next ) {
                $link = $courtsApi->next;
                sleep(10);
            } else {
                break;
            }
        }

    }

    public function curl($link)
    {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $jsonResponse = curl_exec($ch);
        curl_close($ch);

        if ($jsonResponse === false) {
            exit('Failed to connect to API');
        }
        $data = json_decode($jsonResponse);
        return $data;
    }
}