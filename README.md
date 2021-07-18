# Draw a Ticket Test
## The Aim
We have a client who would like to create a prize draw application. 

The aims of this application that we would like you to build are to show
how you would architect such a solution. We will pay particular attention to how you 
organise and implement the application and your test suite. 

* What design patterns will you use to ensure scalability, readability, extensibility and maintainability?
* Will you use CQRS?
* Will you harness Dependency Injection?
* What are your thoughts on Aggregate Roots?
* How will you deal with time?
* How would you structure your tests to test multiple different repository implementations (for example if we introduced
  a doctrine implementation)?
* What linting do you perform on your codebase?

Because of time constraints, we don't necessarily need to see complete implementations involving the above concepts, 
but we may discuss them in a second stage interview and how you would extend the application with these in mind.

The bootstrap application is an out-the-box symfony web application. We do not require you 
to build any of the controllers, but simply concentrate on the domain and infrastructure
aspects.

When you review the code, we have started the implementation off with an 'in memory' repository. We're
looking for you to implement the solution without the need for a database. 

By 'in-memory' we are proposing that the draws be held in an array. This array has been defined for you:
`\App\Infrastructure\Repository\Memory\InMemoryDrawRepository::$draws`

As this is an in-memory repository, we will not concern ourselves with persistence between running commands in real life. 
We will simply be looking at your test suite, therefore you can provide the necessary fixture data in your tests
when testing each command.

We would like you to implement three CLI commands:

`./bin/console dat:create-draw`

`./bin/console dat:enter-ticket`

`./bin/console dat:list-closest-to-target`

### Create Draw
Create and save a draw that has the following:
* Title
* Ticket target
* Date created

### Enter Ticket
Enter a ticket into a draw. You will need to provide:
* Draw ID
* Email of entrant

### List Closest to Target
Running this command should return the draws in order of least remaining first,
for example:

| Draw ID | Target | # Entered | # Remaining |
|---------|--------|-----------|-------------|
| 2       | 20     | 19        |  1          |
| 1       | 50     | 39        | 11          |
| 3       | 100    | 7         | 93          |

**Note**: The output does not need to be tabulated. Output any-which-way is fine.

## Bonus
If you have time, even if it is simply a description or skeleton outline, it 
would be interesting to see how you might introduce a Doctrine persistence 
layer and how you would structure your tests. Describe where you'd use one 
or both of these implementations and the benefits of doing so.

