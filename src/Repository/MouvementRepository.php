<?php

namespace App\Repository;

use App\Entity\Mouvement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mouvement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mouvement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mouvement[]    findAll()
 * @method Mouvement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementRepository extends ServiceEntityRepository
{
    /**
     * MouvementRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mouvement::class);
    }

   /**
    * Retourne les mouvements d'un compte trié par date
    *
    * @return array
    * @access public
    */
    public function getMouvementsCompte($compte_id, $nombreParPage, $page) 
    {
        // On déplace la vérification du numéro de page dans cette méthode
        if ($page < 1) {
          throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
        }

        $query = $this->_em->createQueryBuilder()
    		->select("m")
                ->from("App:Mouvement", "m")
    		->where("m.compte = '".$compte_id."'")
                ->orderBy("m.date", "DESC")
                ->getQuery();

        // On définit l'article à partir duquel commencer la liste
        $query->setFirstResult(($page-1) * $nombreParPage)
              // Ainsi que le nombre d'articles à afficher
              ->setMaxResults($nombreParPage);

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query);

        //return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

   /**
    * Retourne le mouvement s'il existe déjà pour ce compte
    *
    * @return array
    * @access public
    */
    public function getMouvementAutomatiqueCompte($compte_id, $libelle, $date, $credit, $debit) 
    {
        $query = $this->_em->createQueryBuilder()
    		->select("m")
                ->from("App:Mouvement", "m")
    		->where("m.compte = '".$compte_id."'")
    		->andWhere("m.libelle = '".$libelle."'")
    		->andWhere("m.date = '".$date."'")
                ->andWhere("m.credit = '".$credit."'")
    		->andWhere("m.debit = '".$debit."'");

        return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

   /**
    * Retourne si oui ou non le mouvement existe déjà sur le mois en cours du compte
    *
    * @param integer $compte_id
    * @param varchar $libelle
    * @param datetime $date
    * @param varchar $credit
    * @param varchar $debit
    * @param varchar $mois
    * @param boolean $annee_suivante
    * @return boolean
    * @access public
    */
    public function getMouvementCompte($compte_id, $libelle, $numero_jour, $credit, $debit, $mois, $annee_suivante) 
    {
    	
    	if($mois == date('m'))
    	{
    		$date = date('Y').'-'.date('m').'-'.$numero_jour;
    	}
		
        //Si on veut afficher les prochains prelevements automatiques du mois suivant car on est à - de 9 jours du nouveau mois
    	if($mois == date('m')+1)
    	{
    		$date = date('Y').'-'.(date('m')+1).'-'.$numero_jour;
    	}
        	
        //Si on veut afficher les prochains prelevements automatiques du mois suivant spécifique janvier de l'année suivante car on est à - de 9 jours du nouveau mois
    	if($mois == "01" && $annee_suivante == true)
    	{
    		$date = (date('Y')+1).'-01-'.$numero_jour;
    	}

    	//Si on veut afficher les prochains prelevements automatiques du mois en coute spécifique janvier car on est à - de 9 jours du nouveau mois
    	if($mois == "01" && $annee_suivante == false)
    	{
    		$date = (date('Y').'-01-'.$numero_jour);
    	}

    	$mouvement_automatique = $this->_em->getRepository('App:Mouvement')->getMouvementAutomatiqueCompte($compte_id, $libelle, $date, $credit, $debit);

    	if(count($mouvement_automatique) > 0)
            return true;
        return false;
    }
}
