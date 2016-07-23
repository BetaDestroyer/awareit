<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz")
 */
class Quiz 
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="quiz", cascade={"persist"})
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quiz", cascade={"persist", "merge", "remove"}, orphanRemoval=true)
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"persist", "merge", "remove"}, orphanRemoval=true)
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity="UserQuiz", mappedBy="quiz", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    protected $userQuiz;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return Quiz
     */
    public function addCourse(\AppBundle\Entity\Course $course)
    {
        $this->courses[] = $course;

        return $this;
    }

    /**
     * Remove course
     *
     * @param \AppBundle\Entity\Course $course
     */
    public function removeCourse(\AppBundle\Entity\Course $course)
    {
        $this->courses->removeElement($course);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add question
     *
     * @param \AppBundle\Entity\Question $question
     *
     * @return Quiz
     */
    public function addQuestion(\AppBundle\Entity\Question $question)
    {
        $this->question[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \AppBundle\Entity\Question $question
     */
    public function removeQuestion(\AppBundle\Entity\Question $question)
    {
        $this->question->removeElement($question);
    }

    /**
     * Get question
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Add answer
     *
     * @param \AppBundle\Entity\Answer $answer
     *
     * @return Quiz
     */
    public function addAnswer(\AppBundle\Entity\Answer $answer)
    {
        $this->answer[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \AppBundle\Entity\Answer $answer
     */
    public function removeAnswer(\AppBundle\Entity\Answer $answer)
    {
        $this->answer->removeElement($answer);
    }

    /**
     * Get answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Add userQuiz
     *
     * @param \AppBundle\Entity\UserQuiz $userQuiz
     *
     * @return Quiz
     */
    public function addUserQuiz(\AppBundle\Entity\UserQuiz $userQuiz)
    {
        $this->userQuiz[] = $userQuiz;

        return $this;
    }

    /**
     * Remove userQuiz
     *
     * @param \AppBundle\Entity\UserQuiz $userQuiz
     */
    public function removeUserQuiz(\AppBundle\Entity\UserQuiz $userQuiz)
    {
        $this->userQuiz->removeElement($userQuiz);
    }

    /**
     * Get userQuiz
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserQuiz()
    {
        return $this->userQuiz;
    }
}
