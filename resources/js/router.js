let routes = [];
let navbar = [];

//Radio
addRouteToRouter({
  path: '/',
  alias: ['/radio'],
  noNavbar: true,
  component: require('./components/radio/radio.vue').default,
});

//Users
addRouteToRouter({
  path: '/admin/users',
  alias: ['/admin'],
  caption: 'Users',
  component: require('./components/users/users.vue').default,
});

//Players
addRouteToRouter({
  path: '/admin/players',
  alias: [],
  caption: 'Players',
  component: require('./components/players/players.vue').default,
});


//404
routes.push({path: "*", component: require('./components/_juge/juge-404.vue').default});

function addRouteToRouter(args){
  let path = args.path || false;
  let component = args.component || false;
  let caption = args.caption || path.replace("/","");
  let noNavbar = args.noNavbar || false;
  let alias = args.alias || "";
  let roles = args.roles || false;

  routes.push({path, alias, component});

  if(!noNavbar){
    navbar.push({
      link: path,
      caption: caption,
      roles: roles,
    });
  }
}
export default {
  'mode':'history',
  'routes':routes
};
export {navbar as navbar};