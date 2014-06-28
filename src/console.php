<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('Youtube playlist videos app', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

//Import playlist command
$console
	->register('import')
	->setDefinition(array(
		new InputOption('youtube-id', null, InputOption::VALUE_REQUIRED, 'The id of the youtube playlist to import'),
	))
	->setDescription('Imports a full playlist to the Db.')
	->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
		$playlist_id = $input->getOption('youtube-id');
		$playlist_importer = new YourList\Import\Playlist( $app['db'] );

		$playlist_importer->import( $playlist_id );
	})
;

$console
	->register('update')
	->setDefinition(array(
		new InputOption('list-id', null, InputOption::VALUE_REQUIRED, 'The id of the playlist to update'),
	))
	->setDescription('Updates an already imported playlist')
	->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
		$list_id = $input->getOption('list-id');
		$playlist = YourList\Playlist::get($list_id, $app['db']);


		$playlist_importer = new YourList\Import\Playlist( $app['db'] );
		$playlist_importer->import( $playlist['youtube_id'] );
	})
;

$console
	->register('remove')
	->setDefinition(array(
		new InputOption('list-id', null, InputOption::VALUE_REQUIRED, 'The id of the playlist to remove'),
	))
	->setDescription('Updates an already imported playlist')
	->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
		$list_id = $input->getOption('list-id');
		$playlist = YourList\Playlist::get($list_id, $app['db']);
		$playlist->remove();
	})
;

$console
	->register('auto-update')
	->setDefinition(array())
	->setDescription('Updates all the playlists')
	->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {

		$sql = <<<SQL
SELECT
	youtube_id
FROM
	playlist
SQL;

		$list_ids = $app['db']->fetchAll($sql);

		$playlist_importer = new YourList\Import\Playlist( $app['db'] );

		foreach ($list_ids as $list) {
			$playlist_importer->import( $list['youtube_id'] );
		}
	})
;

// Create-db command
$console
    ->register('create-db')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('Creates the database main structure')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

    	$app['db']->query( 'DROP TABLE IF EXISTS user' );
        $sql = <<<SQL
CREATE TABLE user(
	user_id int NOT NULL AUTO_INCREMENT,
	nick varchar(50) NOT NULL,
	pass char(64) NOT NULL,
	user_added timestamp DEFAULT current_timestamp,
	PRIMARY KEY(user_id)
)ENGINE MYISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SQL;
	$app['db']->query($sql);

	$app['db']->query( 'DROP TABLE IF EXISTS playlist' );
	$sql = <<<SQL
CREATE TABLE playlist(
	playlist_id int NOT NULL AUTO_INCREMENT,
	youtube_id varchar(250) NOT NULL,
	title varchar(250) NOT NULL,
	playlist_added timestamp DEFAULT current_timestamp,
	PRIMARY KEY(playlist_id),
	UNIQUE KEY(youtube_id)
)ENGINE MYISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SQL;
	$app['db']->query($sql);

	$app['db']->query( 'DROP TABLE IF EXISTS playlist_video' );
	$sql = <<<SQL
CREATE TABLE playlist_video(
	playlist_id int NOT NULL,
	video_id int NOT NULL AUTO_INCREMENT,
	position int NOT NULL,
	youtube_id varchar(250) NOT NULL,
	title varchar(250) NOT NULL,
	thumbnail varchar(250) NOT NULL,
	video_added timestamp DEFAULT current_timestamp,
	PRIMARY KEY(video_id, playlist_id),
	UNIQUE(youtube_id)
)ENGINE MYISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SQL;
	$app['db']->query($sql);

	$app['db']->query( 'DROP TABLE IF EXISTS user_playlist_video_viewed' );
	$sql = <<<SQL
CREATE TABLE user_playlist_video_viewed(
	user_id int NOT NULL,
	playlist_id int NOT NULL,
	video_id int NOT NULL,
	viewed timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
	PRIMARY KEY(user_id, playlist_id, video_id)
)ENGINE MYISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SQL;
	$app['db']->query($sql);

	$app['db']->query( 'DROP TABLE IF EXISTS user_playlist' );
	$sql = <<<SQL
CREATE TABLE user_playlist(
	user_id int NOT NULL,
	playlist_id int NOT NULL,
	added timestamp DEFAULT current_timestamp,
	PRIMARY KEY(user_id, playlist_id)
)ENGINE MYISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
SQL;
	$app['db']->query($sql);

    })
;

$console
    ->register('update-db-struct')
    ->setDefinition(array(
        // new InputOption('', null, , ''),
    ))
    ->setDescription('Updates the database structure from one deploy to another')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app)
    {
        $sql = <<<SQL
ALTER TABLE
  user
ADD UNIQUE (nick)
SQL;
        $app['db']->query($sql);

    });

$console
    ->register('seed')
    ->setDefinition(array(
        // new InputOption('', null, , ''),
    ))
    ->setDescription('Populates the database with seeded data')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $seeder_manager = new \Yourlist\Seeders\Manager($app['db']);

        $seeder_manager->createFakeData();

    });

return $console;
