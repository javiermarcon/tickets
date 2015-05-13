<?php
namespace Acme\ReservasBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

use Doctrine\Common\Collections;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Obra  // extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    protected $foto;

    /**
     *
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "El maximo tamaño permitido es 5MB.",
     *     mimeTypesMessage = "Solo se puede subir imagenes jpg, gif, png o tiff."
     * )
     */
    protected $archivo;

    /**
     * @ORM\OneToMany(targetEntity="FechaObra", mappedBy="obra", cascade={"persist", "remove"})
     */
    private $fechasobra;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fechasobra = new ArrayCollection(); //\Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Obra
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Obra
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Obra
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    public function setFechasobra(\Doctrine\Common\Collections\Collection $fechasobra)
    {
        $this->fechasobra = $fechasobra;
        foreach ($fechasobra as $fechaobra) {
            $fechaobra->setObra($this);
        }
    }

    /**
     * Get fechasobra
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFechasobra()
    {
        return $this->fechasobra;
    }

    /**
     * Called before saving the entity
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->archivo) {
	    // do whatever you want to generate a unique name
	    //$filename = sha1(uniqid(mt_rand(), true));
	    $this->foto = $this->normalizeString($this->archivo->getClientOriginalName());
//$filename.'.'.$this->archivo->guessExtension();

        }
    }

    /**
     * Called before entity removal
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
	    unlink($file);
        }
    }

    /**
     * Called after entity persistence
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // The file property can be empty if the field is not required
        if (null === $this->archivo) {
	    return;
        }

        // Use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
	//$path = $this->get('kernel')->getRootDir() . '/../public_html/imagenes/fotos';
        //$this->archivo->move(
	//    $this->getUploadRootDir(),
	//    $this->foto
        //);

        // Set the path property to the filename where you've saved the file
        $this->path = $this->normalizeString($this->file->getClientOriginalName());

        // Clean up the file property as you won't need it anymore
        $this->archivo = null;
    }

    public function normalizeString ($str = '')
    {
	$str = strip_tags($str);
	$str = preg_replace('/[\r\n\t ]+/', ' ', $str);
	$str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
	$str = strtolower($str);
	$str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
	$str = htmlentities($str, ENT_QUOTES, "utf-8");
	$str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
	$str = str_replace(' ', '-', $str);
	$str = rawurlencode($str);
	$str = str_replace('%', '-', $str);
	return $str;
    }

    public function __toString()
    {
	return strval($this->getNombre());
    }

    /**
     * Remove fechasobra
     *
     * @param \Acme\ReservasBundle\Entity\FechaObra $fechasobra
     */
    public function removeFechasobra(\Acme\ReservasBundle\Entity\FechaObra $fechasobra)
    {
        $this->fechasobra->removeElement($fechasobra);
    }
}
