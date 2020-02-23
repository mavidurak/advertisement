<?php

namespace MaviDurakIO\Advertisement\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Intervention\Image\ImageManagerStatic as Image;
use Endroid\QrCode\QrCode;

class Instagram extends Command
{
    protected static $defaultName = 'create:instagram';

    protected function configure()
    {
        $this->setDescription('Creates a new Instagram image for an event.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $background = $helper->ask($input, $output, new Question("Backgroud options (1-3): ", '1'));
        $title = $helper->ask($input, $output, new Question("Title (Great Talk): ", 'Great Talk'));
        $speaker = $helper->ask($input, $output, new Question("Speaker (Karl Popper): ", 'Karl Popper'));
        $venue = $helper->ask($input, $output, new Question("Venue (Blue Cafe): ", 'Blue Cafe'));
        $date = $helper->ask($input, $output, new Question("Date (21 October, Friday): ", '21 October, Friday'));
        $hours = $helper->ask($input, $output, new Question("Hours (20:00 - 22:00): ", '20:00 - 22:00'));
        $avatarUrl = $helper->ask($input, $output, new Question("Speaker Avatar (https://avatars3.githubusercontent.com/u/2325140): ", 'https://avatars3.githubusercontent.com/u/2325140'));

        $img = Image::make("assets/back0$background.jpg");

        $img->fit(600, 600);
        
        $img->rectangle(20, 50, 580, 200, function ($draw) {
          $draw->background('rgba(0, 0, 0, 0.62)');
        });
        
        $img->text($title, 300, 70, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(50);
          $font->align('center');
          $font->color('#FFF');
          $font->valign('top');
        });
        
        $img->text($speaker, 300, 150, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(25);
          $font->align('center');
          $font->color('#FFF');
          $font->valign('top');
        });
        
        // Time details
        $img->rectangle(300, 240, 580, 520, function ($draw) {
          $draw->background('rgba(0, 0, 0, 0.62)');
        });
        
        $img->text($date, 440, 300, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(25);
          $font->align('center');
          $font->color('#FFF');
          $font->valign('top');
        });
        
        $img->text($hours, 440, 340, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(25);
          $font->align('center');
          $font->color('#FFF');
          $font->valign('top');
        });
        
        $img->text($venue, 440, 420, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(30);
          $font->align('center');
          $font->color('#FFF');
          $font->valign('top');
        });
        
        // Footer
        $img->rectangle(0, 560, 600, 600, function ($draw) {
          $draw->background('#FFF');
        });
        
        // LOGO
        $logo = Image::make('assets/logo.png');
        $logo->fit(50, 50);
        $img->insert($logo, 'top-left', 20, 556);
        
        // Footer
        $img->text('mavidurak.github.io', 580, 572, function ($font) {
          $font->file('assets/Roboto-Medium.ttf');
          $font->size(20);
          $font->align('right');
          $font->color('#3e5a5b');
          $font->valign('top');
        });
        
        $avatar = Image::make($avatarUrl);
        $avatar->fit(250, 250);
        
        // Masking
        $mask = Image::canvas(250, 250);
        $mask->circle(250, 250/2, 250/2, function ($draw) {
          $draw->background('#fff');
        });
        $avatar->mask($mask, false);
        
        $img->insert($avatar, 'top-left', 20, 250);
        
        $img->save('outputs/instagram.jpg', 100);

        $output->writeln('Instagram image has been created!');
        return 0;
    }
}