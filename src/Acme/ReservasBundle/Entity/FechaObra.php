<?php
namespace Acme\ReservasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class FechaObra
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $fecha;

    /**
     * @ORM\OneToMany(targetEntity="HorarioObra", mappedBy="fechaobra", cascade={"persist", "remove"})
     */
    private $horariosobra;

    /**
     * @ORM\ManyToOne(targetEntity="Obra", inversedBy="fechasobra")
     * @ORM\JoinColumn(name="obras_id", referencedColumnName="id", nullable=false)
     */
    private $obra;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horariosobra = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return FechaObra
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    public function setHorariosobra(\Doctrine\Common\Collections\Collection $horariosobra)
    {
        $this->horariosobra = $horariosobra;
        foreach ($horariosobra as $horarioobra) {
            $horarioobra->setObra($this);
        }
    }

    /**
     * Get horariosObra
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorariosobra()
    {
        return $this->horariosobra;
    }

    /**
     * Get obra
     *
     * @return \Acme/ReservasBundle\Entity\Obra
     */
    public function getObra()
    {
        return $this->obra;
    }

    public function addObras(Obra $obra)
    {
	if (!$this->obras->contains($obra)) {
	    $this->obras->add($obra);
	}
    }

    /**
     * Set obra
     *
     * @param \Acme\ReservasBundle\Entity\Obra $obra
     * @return FechaObra
     */
    public function setObra(\Acme\ReservasBundle\Entity\Obra $obra)
    {
        $this->obra = $obra;

        return $this;
    }

    public function __toString()
    {
	return strval($this->fecha->format('d/m/Y'))." ".$this->obra->getNombre();
    }


    /**
     * Remove horariosobra
     *
     * @param \Acme\ReservasBundle\Entity\HorarioObra $horariosobra
     */
    public function removeHorariosobra(\Acme\ReservasBundle\Entity\HorarioObra $horariosobra)
    {
        $this->horariosobra->removeElement($horariosobra);
    }
}
