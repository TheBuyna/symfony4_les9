<?php


namespace App\Controller;

use App\Service\DatabaseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SpelerController extends AbstractController
{
    private $databaseHelper;

    public function __construct(DatabaseHelper $databaseHelper)
    {
        $this->databaseHelper = $databaseHelper;
    }
    //GET
    /**
     * @Route("/api/spelers", name="api_spelers", methods={"GET"})
     */
    public function GetAll()
    {
        list ( $result, $rows) = $this->GetData();
        $return_result = $result ? "OK" : "Err";

        $response = array( "result" => $return_result,
            "data" => $rows);

        return $this->json($response);
    }

    /**
     * @Route("/api/speler/{id}", name="api_speler", methods={"GET"})
     */
    public function GetOne($id)
    {
        list ( $result, $rows) = $this->GetData($id);
        $return_result = $result ? "OK" : "Err";

        $response = array( "result" => $return_result,
            "data" => $rows);

        return $this->json($response);
    }

    public function GetData($id = null)
    {
        $rows = array();

        $sql = "select * from spelers";
        if ( $id > 0 ) $sql .= " where spe_id=$id";

        $result = $this->databaseHelper->execSqlCommand($sql);
        if ( $result )
        {
            while ( $row = $result->fetch_assoc())
            {
                $rows[] = $row;
            }
        }

        return array( $result, $rows);
    }


    //POST
    /**
     * @Route("/api/spelers", name="api_spelers_new", methods={"POST"})
     */
    public function Post()
    {
        list( $result, $new_id ) = $this->CreateSpeler();

        if ( $result ) $rows = array( "result" => "OK", "msg" => "Speler aangemaakt", "id" => $new_id);
        else           $rows = array( "result" => "Err", "msg" => "Fout bij het aanmaken van deze speler");

        return $this->json($rows);
    }

    public function CreateSpeler()
    {
        $newspeler=$_POST["naam"];

        $sql = "INSERT INTO spelers SET spe_naam='$newspeler'";
        $result = $this->databaseHelper->execSqlCommand($sql);

        $new_id = $this->databaseHelper->VerbindMetMySql()->insert_id;
        return array($result, $new_id);
    }

    //PUT
    /**
     * @Route("/api/speler/{id}", name="api_speler_update", methods={"PUT"})
     */
    public function Put($id)
    {
        $result = $this->UpdateSpeler($id);

        if ( $result ) $rows = array( "result" => "OK", "msg" => "Speler gewijzigd" );
        else           $rows = array( "result" => "Err", "msg" => "Fout bij het updaten van deze speler");

        return $this->json($rows);
    }

    public function UpdateSpeler($id)
    {
        $contents = json_decode( file_get_contents("php://input") );
        $newspeler = $contents->naam;



        $sql = "UPDATE spelers SET spe_naam='$newspeler' where spe_id=$id";
        $result = $this->databaseHelper->execSqlCommand($sql);

        return array($result);
    }

    //DELETE
    /**
     * @Route("/api/speler/{id}", name="api_speler_delete", methods={"DELETE"})
     */
    public function Delete($id)
    {
        $result = $this->RemoveSpeler($id);

        if ( $result ) $rows = array( "result" => "OK", "msg" => "Speler verwijderd" );
        else           $rows = array( "result" => "Err", "msg" => "Fout bij het verwijderen van deze speler");

        return $this->json($rows);
    }

    public function RemoveSpeler($id)
    {

        $sql = "DELETE FROM spelers where spe_id=$id";
        $result = $this->databaseHelper->execSqlCommand($sql);

        return $result;
    }
}