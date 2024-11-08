Denis: conferences, search

Legend:
❌ - not implemented in be/fe
✅ - implemented in fe, be methods are created but needs proper implementation
❓ -

### Guest
- ✅ list all conferences
    - ❌❓ filter conferences based on some filters
    - ❌❓ order conferences
    - ❌❓ search for conference
    - ✅ see details about conference
        - ✅ list lecturers of conference
            - ✅ see details about lecturer (list of lectures, own bio, etc...)
        - ✅ start reservation process without signing in or registering
- ✅ see all my reservations
    - ✅ cancel reservation
- ✅ register (became user)
- ✅ sign in (became user)
- ✅ see all my reservations (store in cookies)

### User
- ✅ list all conferences
    - ✅ see details about conference
        - ✅ list lecturers of conference
            - ✅ see details about lecturer (list of lectures, own bio, etc...)
        - ✅ start reservation process
        - ❌ can offer my own lecture to any future conference and become a potential speaker
- ✅ list all reservations
    - ✅ become visitor without doing anything
    - ❌ pay for a ticket
    - ❌ see if I am potential visitor or confirmed
    - ✅ cancel any of my reservation (potential and confirmed) 
    - ❌ see my schedule
        - ❌ add/delete lectures I want to attend in schedule planner
        - ❌ see details about lectures
- ✅ list lectures
    - ✅ do nothing and because confirmed speaker
    - ❌ see details                        
        - ✅ edit details
        - ❌ add new presentation (plain text)
    - ❌ see statistics (number of user that are planning to attend lecture)
    - ✅ see state (accepted/pending)
    - ✅ cancel lecture
- ✅ list my conferences
    - ✅ create conference
    - ❌ enter conference edit mode
        - ❌ add lecture to my conference
        - ❌ add new rooms to my conference
        - ❌ list all offered lectures
        - ❌ approve offered lectures, set time and place
        - ❌ list all potential and confirmed viewers
        - ❌ change state of viewers from potential to confirmed
    - ✅ see details about conference
        - ✅ list lecturers of conference
            - ✅ see details about lecturer (list of lectures, own bio, etc...)
- ✅ logout

### Administrator
- ❌ list users
    - ❌ set user roles
    - ❌ create new users
    - ❌ delete users
- ❌❓ list conferences
    - ❌❓ delete conference
    - ❌❓ edit conference
    - ❌❓ list lectures
        - ❌❓ delete lecture
        - ❌❓ edit lecture
- ✅ logout
- ✅ do everything that users and guest can do
