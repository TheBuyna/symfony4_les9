<?php


namespace App\Controller;

use App\Service\DatabaseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SpelerController extends AbstractController
{
    private $db;

    public function __construct(DatabaseHelper $db)
    {
        $this->db = $db;
    }

    //GET
    /**
     * @Route("/api/spelers", name="api_spelers", methods={"GET"})
     */
    public function GetAll()
    {
        $result = $this->GetData();
        $return_result = $result ? "OK" : "Err";

        $response = array( "result" => $return_result,
            "data" => $this->db->rows);

        return $this->json($response);
    }

    /**
     * @Route("/api/speler/{id}", name="api_speler", methods={"GET"})
     */
    public function GetOne($id)
    {
        $result = $this->GetData($id);
        $return_result = $result ? "OK" : "Err";

        $response = array( "result" => $return_result,
            "data" => $this->db->rows);

        return $this->json($response);
    }

    public function GetData($id = null)
    {
        $sql = "select * from spelers";
        if ( $id > 0 ) $sql .= " where spe_id=$id";
        return  $this->db->exec($sql);
    }

    //POST
    /**
     * @Route("/api/spelers", name="api_spelers_new", methods={"POST"})
     */
    public function Post()
    {
        $result = $this->CreateSpeler();

        if ( $result ) $rows = array( "result" => "OK", "msg" => "Speler aangemaakt", "id" => $this->db->new_id);
        else           $rows = array( "result" => "Err", "msg" => "Fout bij het aanmaken van deze speler");

        return $this->json($rows);
    }

    public function CreateSpeler()
    {
        $newspeler=$_POST["naam"];
        $sql = "INSERT INTO spelers SET spe_naam='$newspeler'";
        return $this->db->exec($sql);
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
        return $this->db->exec($sql);
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
        return $this->db->exec($sql);
    }

}