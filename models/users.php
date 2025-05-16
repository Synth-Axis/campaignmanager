<?php

require_once("dbconfig.php");

class Users extends Base
{

	public function getAllUsers()
	{
		$query = $this->db->prepare("
			SELECT 
				*
			FROM 
				users
		");

		$query->execute();

		return $query->fetchAll();
	}

	public function findUserByEmail($email)
    {
        $query = $this->db->prepare("
			SELECT 
                *
			FROM 
				users
            WHERE
                email = ?
		");

        $query->execute([$email]);

        return $query->fetch();
    }

	public function RegisterUser($formData) {

        $query = $this->db->prepare("
            INSERT INTO users
                (nome, email, password)
                VALUES(?, ?, ?)
            ");

        $query->execute (
            [
				$formData["nome"],
                $formData["email"], 
                $formData["password"]
            ]
        );
    }



	/*
	public function getLatestGames() {
		
		$query = $this->db->prepare("
			SELECT 
				game_id, game_name, released_on, game_photo
			FROM 
				games
			WHERE DATE(released_on) >= '2018-01-01'
		");
		
		$query->execute();
		
		return $query->fetchAll();
	}

	public function getPreviousGames() {
		
		$query = $this->db->prepare("
			SELECT 
				game_id, game_name, released_on, game_photo
			FROM 
				games
			WHERE DATE(released_on) < '2018-01-01'
		");
		
		$query->execute();
		
		return $query->fetchAll();
	}

	public function getGameDetail($id){

		$query = $this->db->prepare("
			SELECT
				*
			FROM 
				games
			WHERE 
				game_id= ?

		");

		$query->execute([ $id ]);

		return $query->fetch();

	}

	public function findGamesByPlatform($platformId){
		$query = $this->db->prepare("
		SELECT
			g.game_id, g.game_name, g.released_on, g.game_photo, gp.game_id, gp.platform_id
		FROM
			games_platforms AS gp   
		INNER JOIN
			platforms as p ON(p.platform_id = gp.platform_id)
		INNER JOIN
			games as g ON(g.game_id = gp.game_id)
		WHERE
			p.platform_id = ?
		");

		$query->execute( [$platformId] );
		
		return $query->fetchAll();
	}

	public function findGamesByGenre($genreId){
		$query = $this->db->prepare("
			SELECT
				g.game_id, g.game_name, g.released_on, g.game_photo, gr.game_id, gr.genre_id, gen.genre_name
			FROM
				genres_games AS gr   
			INNER JOIN
				genres as gen ON(gen.genre_id = gr.genre_id)
			INNER JOIN
				games as g ON(g.game_id = gr.game_id)
			WHERE
				gen.genre_id = ?
		");

		$query->execute( [$genreId] );
		
		return $query->fetchAll();
	}


	public function getTopRated(){

        $query = $this->db->prepare("

		SELECT
			g.game_id, g.game_name, g.game_photo, g.released_on , AVG(r.rating_score) AS averageScore
		FROM 
			games AS g
		INNER JOIN
			rated_games AS rg ON(rg.game_id = g.game_id)
		INNER JOIN
			ratings AS r ON(r.rating_id = rg.rating_id)
        GROUP BY 
			g.game_id, g.game_name, g.game_photo, g.released_on 
		HAVING  
			AVG(r.rating_score) >= 3.5
        ");

        $query->execute();
		
		return $query->fetchAll();
    }

	public function getPreviousTopRated(){

        $query = $this->db->prepare("

		SELECT
			g.game_id, g.game_name, g.game_photo, g.released_on , AVG(r.rating_score) AS averageScore
		FROM 
			games AS g
		INNER JOIN
			rated_games AS rg ON(rg.game_id = g.game_id)
		INNER JOIN
			ratings AS r ON(r.rating_id = rg.rating_id)
        GROUP BY 
			g.game_id, g.game_name, g.game_photo, g.released_on 
		HAVING  
			AVG(r.rating_score) < 3.5
        ");

        $query->execute();
		
		return $query->fetchAll();
    }

	public function searchGames($searchString){

        $query = $this->db->prepare("
			SELECT * 
				FROM games 
			WHERE game_name LIKE ?
        ");

        $query->execute([$searchString]);
		
		return $query->fetchAll();
    }

	public function findScreenshots($gameId){

        $query = $this->db->prepare("
			SELECT 
				screenshot_image
			FROM 
				screenshots 
			WHERE 
				game_id = ?
        ");

        $query->execute([$gameId]);
		
		return $query->fetchAll();
    }

	public function deleteGame($gameId){

        $query = $this->db->prepare("
			DELETE FROM games  
			WHERE game_id = ?
        ");

        $query->execute([$gameId]);
    }

	public function addGame($game_name, $released_on, $game_photo){

        $query = $this->db->prepare("
			INSERT INTO games  
			(game_name, released_on, game_photo)
			VALUES(?, ?, ?)
        ");

        $query->execute([
			$game_name,
			$released_on,
			$game_photo
		]);
    }

	public function findGamesAndGenres(){
		$query = $this->db->prepare("
			SELECT
				g.game_id, g.game_name, gr.game_id, gr.genre_id, gen.genre_name
			FROM
				genres_games AS gr   
			INNER JOIN
				genres as gen ON(gen.genre_id = gr.genre_id)
			INNER JOIN
				games as g ON(g.game_id = gr.game_id)
			ORDER BY
				g.game_name
		");

		$query->execute();
		
		return $query->fetchAll();
	}

	public function findGamesAndPlatforms(){
		$query = $this->db->prepare("
			SELECT
				g.game_id, g.game_name, pg.game_id, pg.platform_id, pl.platform_name
			FROM
				games_platforms AS pg   
			INNER JOIN
				platforms as pl ON(pl.platform_id = pg.platform_id)
			INNER JOIN
				games as g ON(g.game_id = pg.game_id)
			ORDER BY
				g.game_name
		");

		$query->execute();
		
		return $query->fetchAll();
	}
*/
}
