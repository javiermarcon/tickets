<?php
namespace Acme\ReservasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\ReservasBundle\Entity\Fechasobra;

/**
 * @ORM\Entity
 */
class HorarioObra
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="time", nullable=false)
     */
    private $hora;

    /**
     * @ORM\ManyToOne(targetEntity="FechaObra", inversedBy="horariosobra")
     * @ORM\JoinColumn(name="fechas_id", referencedColumnName="id", nullable=false)
     */
    private $fechaobra;

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
     * Set hora
     *
     * @param \DateTime $hora
     * @return HorariosObra
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set fechaobra
     *
     * @param \Acme/ReservasBundle\Entity\Entity\FechaObra $fechaObra
     * @return HorarioObra
     */
    public function setFechaobra( $fechaobra)
    {
        $this->fechaobra = $fechaobra;

        return $this;
    }

    /**
     * Get fechaobra
     *
     * @return \Acme/ReservasBundle\Entity\FechaObra
     */
    public function getFechaobra()
    {
        return $this->fechaobra;
    }

}
