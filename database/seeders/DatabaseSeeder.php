<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\RoleType;
use App\Enums\Themes;
use App\Models\Conference;
use App\Models\Lecture;
use App\Models\LectureSchedule;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        Conference::factory(5)->create();
        Room::factory(10)->create();

        $this->setupUser(
            'pepa@pepa.com',
            'pepa',
            'Pepa',
            'Pepik',
            RoleType::User->value,
            15,
            25,
            5
        );

        $this->setupUser(
            'franta@franta.com',
            'franta',
            'Franta',
            'František',
            RoleType::User->value,
            0,
            0,
            30
        );

        User::factory(40)->create();

        Conference::factory(10)->create();
        Lecture::factory(10)->create();
        Reservation::factory(20)->create();

        Room::factory(10)->create();
        LectureSchedule::factory(30)->create();

        $realWorldSeedsUser = $this->setupUser(
            'admin@admin.com',
            'admin',
            'Admin',
            'Admin',
            RoleType::Admin->value,
            0,
            0,
            10
        );


        self::realWorldSeeds($realWorldSeedsUser->id);
    }

    private function setupUser($email, $password, $name, $surname, $role, $conferences, $lectures, $reservations) {
        $user = User::create([
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role
        ]);

        $allThemes = Themes::cases();
        $count = count(Themes::cases());
        $bankAccount = 'CZ' . ((string)fake()->numberBetween(100000000000000000000, 999999999999999999999));

        if($conferences > 0) {
            // create atleast one past conference
            $theme = $allThemes[fake()->numberBetween(0, $count - 1)];
            $start_time = fake()->dateTimeBetween('-6 months', '-1 months');
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));
            $conference = Conference::create([
                'title' => fake()->sentence(),
                'description' => fake()->paragraphs(3, true),
                'theme' => $theme->value,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'place_address' => fake()->address(),
                'price' => fake()->randomFloat(2, 0, 10000),
                'capacity' => fake()->numberBetween(1, 10000),
                'owner_id' => $user->id,
                'poster' => 'https://picsum.photos/seed/' . $theme->value . '/1200/400',
                'bank_account' => $bankAccount,
            ]);

            $start_time = fake()->dateTimeBetween($conference->start_time, $conference->end_time);
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+6 hours'));
            $title = fake()->sentence();

            Lecture::create([
                'title' => $title,
                'description' => fake()->paragraphs(3, true),
                'is_confirmed' => true,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'speaker_id' => $user->id,
                'conference_id' => $conference->id,
                'room_id' => Room::all()->random()->id,
                'poster' => 'https://picsum.photos/seed/' . $title . '/1200/400',
            ]);
        }

        for($i = 0; $i < $conferences - 1; $i++) {
            $theme = $allThemes[fake()->numberBetween(0, $count - 1)];
            $start_time = fake()->dateTimeBetween('-4 months', '+12 months');
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));
            $adminConference = Conference::create([
                'title' => fake()->sentence(),
                'description' => fake()->paragraphs(3, true),
                'theme' => $theme->value,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'place_address' => fake()->address(),
                'price' => fake()->randomFloat(2, 0, 10000),
                'capacity' => fake()->numberBetween(1, 10000),
                'owner_id' => $user->id,
                'poster' => 'https://picsum.photos/seed/' . $theme->value . '/1200/400',
                'bank_account' => $bankAccount,
            ]);


            $start_time = fake()->dateTimeBetween($adminConference->start_time, $adminConference->end_time);
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+6 hours'));
            $title = fake()->sentence();

            Lecture::create([
                'title' => $title,
                'description' => fake()->paragraphs(3, true),
                'is_confirmed' => fake()->boolean(),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'speaker_id' => User::all()->random()->id,
                'conference_id' => $adminConference->id,
                'room_id' => Room::all()->random()->id,
                'poster' => 'https://picsum.photos/seed/' . $title . '/1200/400',
            ]);
        }

        for($i = 0; $i < $lectures; $i++) {
            $conference = Conference::all()->random();
            $start_time = fake()->dateTimeBetween($conference->start_time, $conference->end_time);
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+6 hours'));
            $title = fake()->sentence();

            Lecture::create([
                'title' => $title,
                'description' => fake()->paragraphs(3, true),
                'is_confirmed' => fake()->boolean(),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'speaker_id' => $user->id,
                'conference_id' => $conference->id,
                'room_id' => Room::all()->random()->id,
                'poster' => 'https://picsum.photos/seed/' . $title . '/1200/400',
            ]);
        }

        for($i = 0; $i < $reservations; $i++) {
            $conference = Conference::all()->random();
            Reservation::create([
                'is_confirmed' => fake()->boolean(),
                'user_id' => $user->id,
                'conference_id' => $conference->id,
            ]);
        }

        return $user;
    }

    private function realWorldSeeds($userId) {
        foreach(self::realWorldConferences as $item) {

            $start_time = fake()->dateTimeBetween('+0 months', '+3 months');
            $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+2 days'));
            $bankAccount = 'CZ' . ((string)fake()->numberBetween(100000000000000000000, 999999999999999999999));
            $conference = Conference::create([
                'title' => $item['title'],
                'description' => $item['desc'],
                'theme' => $item['theme'],
                'start_time' => $start_time,
                'end_time' => $end_time,
                'place_address' => fake()->address(),
                'price' => fake()->randomFloat(2, 0, 10000),
                'capacity' => fake()->numberBetween(1, 10000),
                'owner_id' => $userId,
                'poster' => $item['poster'],
                'bank_account' => $bankAccount,
            ]);

            foreach($item['lectures'] as $lecture) {
                $start_time = fake()->dateTimeBetween($conference->start_time, $conference->end_time);
                $end_time = fake()->dateTimeBetween($start_time, (clone $start_time)->modify('+6 hours'));
                Lecture::create([
                    'title' => $lecture['title'],
                    'description' => $lecture['desc'],
                    'is_confirmed' => fake()->boolean(),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'speaker_id' => $userId,
                    'conference_id' => $conference->id,
                    'room_id' => Room::all()->random()->id,
                    'poster' => $lecture['poster'],
                ]);
            }

            for($i = 0; $i < 10; $i++) {
                $conference = Conference::all()->random();
                Reservation::create([
                    'is_confirmed' => fake()->boolean(),
                    'user_id' => User::all()->random()->id,
                    'conference_id' => $conference->id,
                ]);
            }
        }
    }

    const realWorldConferences =
    [
        [
            "title" => "AI & ML Horizons: Unlocking the Future of Intelligent Systems",
            'theme' => "Artificial Intelligence and Machine Learning",
            "desc" => "Join us at the premier conference on Artificial Intelligence and Machine Learning, where innovation meets expertise. Explore groundbreaking research, transformative applications, and cutting-edge advancements in AI and ML technologies. This event brings together visionaries, researchers, and practitioners from around the globe to share insights, discuss trends, and inspire innovation. Whether you're a seasoned expert or an enthusiastic newcomer, immerse yourself in keynotes, interactive workshops, and engaging lectures tailored to fuel your passion for intelligent systems. Be part of the conversation shaping the future of AI and ML. Reserve your spot today!",
            "poster" => "https://media.geeksforgeeks.org/wp-content/uploads/20230911173805/What-is-Artiificial-Intelligence(AI).webp",
            "lectures" => [
                [
                    "title" => "Ethics in AI: Navigating Bias, Privacy, and Responsibility",
                    "desc" => "As AI systems become increasingly integrated into our lives, addressing ethical challenges has never been more critical. This lecture explores the complexities of building fair, unbiased, and accountable AI models. Attendees will gain insights into identifying algorithmic biases, safeguarding user privacy, and ensuring transparency in decision-making. Through real-world case studies and interactive discussions, we’ll examine how ethics shape the development and deployment of AI technologies. Whether you're an AI practitioner or a policymaker, this session equips you with tools to ensure your AI solutions uphold ethical standards while delivering impactful results.",
                    "poster" => "https://store.prnewsonline.com/wp-content/uploads/2020/07/AI_Signal-webinar.png",
                ],
                [
                    "title" => "Transformative Power of Deep Learning: From Vision to Language",
                    "desc" => "Delve into the fascinating world of deep learning and its applications in computer vision and natural language processing (NLP). This lecture highlights key breakthroughs in convolutional neural networks (CNNs), transformer architectures, and generative models like GPT. Participants will explore how these technologies power innovations such as image recognition, autonomous vehicles, chatbots, and content generation. Gain a deeper understanding of how to optimize neural networks, address challenges like overfitting, and push the boundaries of what deep learning can achieve. Ideal for both beginners and experts, this session bridges theory with practice in the rapidly evolving field of AI.",
                    "poster" => "https://cdn.corporatefinanceinstitute.com/assets/artificial-intelligence-1024x512.jpeg",
                ],
                [
                    "title" => "AI in Healthcare: Revolutionizing Diagnosis and Patient Care",
                    "desc" => "Discover how AI is transforming the healthcare industry by enabling faster diagnoses, personalized treatments, and efficient patient care. This lecture examines the use of machine learning algorithms in medical imaging, drug discovery, and wearable health technologies. Attendees will explore success stories, from AI detecting diseases like cancer at an early stage to optimizing hospital operations. We’ll also discuss the regulatory and ethical considerations unique to healthcare AI applications. Whether you’re a healthcare professional or a tech enthusiast, learn how AI is driving the future of medicine and what it takes to implement AI solutions in this critical sector.",
                    "poster" => "https://www.hhmglobal.com/wp-content/uploads/2020/10/AI_healthcare.jpg",
                ],
            ],
        ],
        [
            "title" => "A plan to mastering productivity: Strategies for Optimal Time Management",
            "theme" => "Productivity and Time Management",
            "desc" => "In a world filled with endless notifications, emails, and multitasking demands, staying focused has become a superpower. This lecture delves into the neuroscience of attention, exploring how our brains respond to distractions and what we can do to maintain deep focus. Learn about techniques like the Pomodoro Technique, time-blocking, and digital detox strategies to reclaim your attention. Through real-world examples and hands-on exercises, participants will leave equipped with practical methods to minimize distractions and create a focus-driven work environment. Perfect for anyone looking to boost concentration and productivity in their personal or professional life.",
            "poster" => "https://www.utep.edu/extendeduniversity/utepconnect/blog/april-2017/time-management-header.jpg",
            "lectures" => [
                [
                    "title" => "The Science of Focus: Overcoming Distractions in a Digital World",
                    "desc" => "In a world filled with endless notifications, emails, and multitasking demands, staying focused has become a superpower. This lecture delves into the neuroscience of attention, exploring how our brains respond to distractions and what we can do to maintain deep focus. Learn about techniques like the Pomodoro Technique, time-blocking, and digital detox strategies to reclaim your attention. Through real-world examples and hands-on exercises, participants will leave equipped with practical methods to minimize distractions and create a focus-driven work environment. Perfect for anyone looking to boost concentration and productivity in their personal or professional life.",
                    "poster" => "https://www.rismedia.com/wp-content/uploads/2018/12/time_management_888677832.jpg",
                ],
                [
                    "title" => "The Art of Prioritization: Identifying What Truly Matters",
                    "desc" => "Do you often feel overwhelmed by endless tasks and competing priorities? This lecture introduces frameworks like the Eisenhower Matrix, Pareto Principle, and OKR (Objectives and Key Results) to help you identify and focus on what truly matters. Gain insights into how high-performing individuals and teams prioritize effectively and align their tasks with their larger goals. Participants will also learn strategies for saying no to low-value activities and managing workload without burning out. Whether you're a busy professional, a team leader, or a student, this session provides tools to organize your day and achieve meaningful outcomes.",
                    "poster" => "https://alliedmedtraining.com/wp-content/uploads/Time-management.jpg",
                ],
                [
                    "title" => "Building Habits for Lasting Productivity: Small Changes, Big Results",
                    "desc" => "Sustainable productivity starts with the right habits. This lecture explores the psychology of habit formation and how small, consistent actions can lead to significant improvements over time. Discover how to build a morning routine that sets the tone for a productive day, leverage habit-stacking techniques, and avoid common pitfalls like procrastination. Drawing on research from behavioral science and personal development, attendees will learn how to create a personalized plan for long-term productivity. Whether you're looking to break bad habits or build better ones, this session offers actionable insights for lasting change.",
                    "poster" => "https://stunningmotivation.com/wp-content/uploads/2018/10/good-time-management-habits.png",
                ],
            ],
        ],
        [
            "title" => "AI in Film: Exploring the Future of Film and Media",
            "theme" => "Film and Media",
            "desc" => "Step into the world of storytelling and innovation at the Film and Media conference. Explore the ever-evolving landscape of cinema, streaming, and digital content creation with industry leaders, visionary creators, and media experts. This conference offers a deep dive into the latest trends, technologies, and challenges shaping film and media today. Through thought-provoking lectures, panel discussions, and hands-on workshops, you’ll gain valuable insights into storytelling techniques, production strategies, and audience engagement. Whether you're a filmmaker, content creator, or media enthusiast, this event is your backstage pass to the future of entertainment. Reserve your spot now and be part of the story!",
            "poster" => "https://www.redsharknews.com/hubfs/ai%20film%20production.jpg",
            "lectures" => [
                [
                    "title" => "Cinematic Storytelling in the Digital Age: Keeping Audiences Engaged",
                    "desc" => "In an era of endless streaming options and short attention spans, how can filmmakers craft stories that truly resonate? This lecture explores the art and science of storytelling in today’s digital world. Learn how to structure compelling narratives, create memorable characters, and evoke emotional connections that captivate modern audiences. Discover how platforms like TikTok, YouTube, and streaming services influence storytelling styles and audience expectations. With examples from iconic films and viral content, this session equips creators with tools to craft stories that shine in an oversaturated media landscape.",
                    "poster" => "https://media.graphassets.com/aOC4Ufd1SeNtK7GiGrbp",
                ],
                [
                    "title" => "The Rise of Virtual Production: Redefining the Filmmaking Process",
                    "desc" => "From the groundbreaking techniques used in The Mandalorian to independent creators leveraging virtual tools, virtual production is revolutionizing the way films are made. This lecture dives into the technology behind LED stages, real-time rendering, and motion capture, explaining how these innovations are transforming pre-production, filming, and post-production processes. Participants will explore the cost, creativity, and collaboration benefits of virtual production, as well as its challenges and limitations. Whether you're a seasoned filmmaker or a student of the craft, this session provides a roadmap for integrating virtual production into your projects.",
                    "poster" => "https://mbrellafilms.com/wp-content/uploads/2019/07/VIETNAM-FILM-PRODUCTION-LOCATIONS.jpg",
                ],
                [
                    "title" => "The Business of Media: Monetizing Creativity in a Changing Industry",
                    "desc" => "The film and media industry is rapidly evolving, with traditional revenue models being disrupted by streaming services, social platforms, and new distribution methods. This lecture examines how creators and studios can navigate these changes and monetize their work effectively. Topics include crowdfunding, subscription models, merchandise, and leveraging social media for audience building and direct-to-consumer sales. Attendees will gain insights into how independent creators are thriving and how major studios are adapting to maintain profitability. Whether you're a content creator, producer, or entrepreneur, this session offers actionable strategies to turn your creative vision into a sustainable career.",
                    "poster" => "https://blog.filmustage.com/content/images/2023/06/Film-production-management-decoded---Lessons-from-iconic-films.png",
                ],
                [
                    "title" => "Diversity in Media: Telling Authentic Stories for a Global Audience",
                    "desc" => "As audiences become more diverse and global, the demand for authentic representation in film and media has never been greater. This lecture explores the importance of inclusivity in storytelling and the business case for diverse narratives. Learn how filmmakers and media creators can authentically portray underrepresented voices, avoid stereotypes, and connect with audiences across cultures. Using case studies from successful films and media campaigns, this session will delve into the creative, social, and commercial impact of inclusive storytelling. Whether you're an aspiring creator or an industry veteran, gain practical insights into building stories that reflect the richness of the world we live in.",
                    "poster" => "http://www.planetgreenstudios.com/images/1_Film_Production.jpg",
                ],
            ],
        ],
    ];
}
