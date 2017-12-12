# missing-link
This is a class project for Ryan Shaw's INLS 620: Web Information Organization.

**__This app requires PHP > 5.6, a live version is deployed at: https://opal.ils.unc.edu/~ttuttle/missing-link/__**

## Resources
### Instructors
**Schema:** http://schema.org/Person
**Attributes:** Name, Identifier

### Courses
**Schema:** http://schema.org/Course
**Attributes:**
-name: name of course
-description: text about the course
-coursePrerequisites: list of prerequisites if available
-workExample: link to syllabus
-provider: @type Person, links back to the page for the instructor

## Classes
**linked-results** - links to courses taught by instructors on their homepage, and vice versa
**results-list** - list of all resources of a particular type
**result-detail** - indicates a section providing details about a particular entity
**add-new** - section to add a new resource to the application, surrounds a form element

## Others
For input elements, I used for for and id and matching name attributes to link the hypermedia controls. But in general, I tried to use the HTML5 elements to indicate how a section is related to the other sections. For example, the "linked-results" section is nested within the "results-detail" section.``````
